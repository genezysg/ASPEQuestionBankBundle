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

class ProvaController extends FOSRestController {
	/**
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
	 * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")
	 */
	public function getProvasAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'question.prova.handler' )->all ( $limite, $posicao_inicio );
	}
	public function getProvaAction($codigo) {
		if (! ($prova = $this->container->get ( 'question.prova.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $codigo ) );
		}
		
		return $prova;
	}
	public function postProvaAction(Request $request) {
		try {
			$newProva = $this->container->get ( 'question.prova.handler' )->post ( $request->request->all () );
			
			$routeOptions = array (
					'codigo' => $newProva->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_prova', $routeOptions );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	public function deleteProvaAction($codigo, Request $request, ParamFetcherInterface $paramFetcher) {
		try {
			if ($usuario = $this->container->get ( 'question.prova.handler' )->get ( $codigo )) {
				$statusCode = Codes::HTTP_CREATED;
				$this->container->get ( 'question.prova.handler' )->delete ( $usuario );
			} else
				$statusCode = Codes::HTTP_NO_CONTENT;
			$routeOptions = array (
					'_format' => $request->get ( '_format' ) 
			);
			return $this->routeRedirectView ( 'aspe_get_provas', $routeOptions, $statusCode );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	/**
	 * @Annotations\View(templateVar = "form")
     */
	public function putProvaAction(Request $request, $codigo) {
		try {
			if (! ($prova = $this->container->get ( 'question.prova.handler' )->get ( $codigo ))) {				
				$prova = $this->container->get ( 'question.prova.handler' )->post ( $request->request->all () );
				$statusCode = Codes::HTTP_CREATED;
			} else {
				$statusCode = Codes::HTTP_NO_CONTENT;
				$prova = $this->container->get ( 'question.prova.handler' )->put ( $prova, $request->request->all () );
			}
			
			$routeOptions = array (
					'codigo' => $prova->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_prova', $routeOptions, $statusCode);
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	public function patchProvaAction(Request $request, $codigo) {
		try {			
			$prova = $this->container->get ( 'question.prova.handler' )->patch ( $this->getProvaAction ( $codigo ), $request->request->all () );
			
			$routeOptions = array (
					'codigo' => $prova->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_prova', $routeOptions, Codes::HTTP_OK );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
}