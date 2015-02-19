<?php

namespace QuestionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use QuestionBundle\Exception\InvalidFormException;

class AutorDaQuestaoController extends FOSRestController {
	/**
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
	 * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")
	 */
	public function getAutorDaQuestaosAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'question.autor_questao.handler' )->all ( $limite, $posicao_inicio );
	}
	public function getAutorDaQuestaoAction($codigo) {
		if (! ($autor_questao = $this->container->get ( 'question.autor_questao.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $codigo ) );
		}
		
		return $autor_questao;
	}
	public function postAutorDaQuestaoAction(Request $request) {
		try {
			$newAutorDaQuestao = $this->container->get ( 'question.autor_questao.handler' )->post ( $request->request->all () );
			
			$routeOptions = array (
					'codigo' => $newAutorDaQuestao->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_autor_questao', $routeOptions );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	public function deleteAutorDaQuestaoAction($codigo, Request $request, ParamFetcherInterface $paramFetcher) {
		try {
			if ($usuario = $this->container->get ( 'question.autor_questao.handler' )->get ( $codigo )) {
				$statusCode = Codes::HTTP_CREATED;
				$this->container->get ( 'question.autor_questao.handler' )->delete ( $usuario );
			} else
				$statusCode = Codes::HTTP_NO_CONTENT;
			$routeOptions = array (
					'_format' => $request->get ( '_format' ) 
			);
			return $this->routeRedirectView ( 'aspe_get_autor_questaos', $routeOptions, $statusCode );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	/**
	 * @Annotations\View(templateVar = "form")
     */
	public function putAutorDaQuestaoAction(Request $request, $codigo) {
		try {
			if (! ($autor_questao = $this->container->get ( 'question.autor_questao.handler' )->get ( $codigo ))) {				
				$autor_questao = $this->container->get ( 'question.autor_questao.handler' )->post ( $request->request->all () );
				$statusCode = Codes::HTTP_CREATED;
			} else {
				$statusCode = Codes::HTTP_NO_CONTENT;
				$autor_questao = $this->container->get ( 'question.autor_questao.handler' )->put ( $autor_questao, $request->request->all () );
			}
			
			$routeOptions = array (
					'codigo' => $autor_questao->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_autor_questao', $routeOptions, $statusCode);
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	public function patchAutorDaQuestaoAction(Request $request, $codigo) {
		try {			
			$autor_questao = $this->container->get ( 'question.autor_questao.handler' )->patch ( $this->getAutorDaQuestaoAction ( $codigo ), $request->request->all () );
			
			$routeOptions = array (
					'codigo' => $autor_questao->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_autor_questao', $routeOptions, Codes::HTTP_OK );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
}