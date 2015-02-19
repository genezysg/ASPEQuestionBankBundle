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

class OpcaoController extends FOSRestController {
	/**
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
	 * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")
	 */
	public function getOpcaosAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'question.opcao.handler' )->all ( $limite, $posicao_inicio );
	}
	public function getOpcaoAction($codigo) {
		if (! ($opcao = $this->container->get ( 'question.opcao.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $codigo ) );
		}
		
		return $opcao;
	}
	public function postOpcaoAction(Request $request) {
		try {
			$newOpcao = $this->container->get ( 'question.opcao.handler' )->post ( $request->request->all () );
			
			$routeOptions = array (
					'codigo' => $newOpcao->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_opcao', $routeOptions );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	public function deleteOpcaoAction($codigo, Request $request, ParamFetcherInterface $paramFetcher) {
		try {
			if ($usuario = $this->container->get ( 'question.opcao.handler' )->get ( $codigo )) {
				$statusCode = Codes::HTTP_CREATED;
				$this->container->get ( 'question.opcao.handler' )->delete ( $usuario );
			} else
				$statusCode = Codes::HTTP_NO_CONTENT;
			$routeOptions = array (
					'_format' => $request->get ( '_format' ) 
			);
			return $this->routeRedirectView ( 'aspe_get_opcaos', $routeOptions, $statusCode );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	/**
	 * @Annotations\View(templateVar = "form")
     */
	public function putOpcaoAction(Request $request, $codigo) {
		try {
			if (! ($opcao = $this->container->get ( 'question.opcao.handler' )->get ( $codigo ))) {				
				$opcao = $this->container->get ( 'question.opcao.handler' )->post ( $request->request->all () );
				$statusCode = Codes::HTTP_CREATED;
			} else {
				$statusCode = Codes::HTTP_NO_CONTENT;
				$opcao = $this->container->get ( 'question.opcao.handler' )->put ( $opcao, $request->request->all () );
			}
			
			$routeOptions = array (
					'codigo' => $opcao->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_opcao', $routeOptions, $statusCode);
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	public function patchOpcaoAction(Request $request, $codigo) {
		try {			
			$opcao = $this->container->get ( 'question.opcao.handler' )->patch ( $this->getOpcaoAction ( $codigo ), $request->request->all () );
			
			$routeOptions = array (
					'codigo' => $opcao->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_opcao', $routeOptions, Codes::HTTP_OK );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
}