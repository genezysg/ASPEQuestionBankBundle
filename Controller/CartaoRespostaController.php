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

class CartaoRespostaController extends FOSRestController {
	/**
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
	 * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")
	 */
	public function getCartaoRespostasAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'question.cartao_resposta.handler' )->all ( $limite, $posicao_inicio );
	}
	public function getCartaoRespostaAction($codigo) {
		if (! ($cartao_resposta = $this->container->get ( 'question.cartao_resposta.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $codigo ) );
		}
		
		return $cartao_resposta;
	}
	public function postCartaoRespostaAction(Request $request) {
		try {
			$newCartaoResposta = $this->container->get ( 'question.cartao_resposta.handler' )->post ( $request->request->all () );
			
			$routeOptions = array (
					'codigo' => $newCartaoResposta->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_cartao_resposta', $routeOptions );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	public function deleteCartaoRespostaAction($codigo, Request $request, ParamFetcherInterface $paramFetcher) {
		try {
			if ($usuario = $this->container->get ( 'question.cartao_resposta.handler' )->get ( $codigo )) {
				$statusCode = Codes::HTTP_CREATED;
				$this->container->get ( 'question.cartao_resposta.handler' )->delete ( $usuario );
			} else
				$statusCode = Codes::HTTP_NO_CONTENT;
			$routeOptions = array (
					'_format' => $request->get ( '_format' ) 
			);
			return $this->routeRedirectView ( 'aspe_get_cartao_respostas', $routeOptions, $statusCode );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	/**
	 * @Annotations\View(templateVar = "form")
     */
	public function putCartaoRespostaAction(Request $request, $codigo) {
		try {
			if (! ($cartao_resposta = $this->container->get ( 'question.cartao_resposta.handler' )->get ( $codigo ))) {				
				$cartao_resposta = $this->container->get ( 'question.cartao_resposta.handler' )->post ( $request->request->all () );
				$statusCode = Codes::HTTP_CREATED;
			} else {
				$statusCode = Codes::HTTP_NO_CONTENT;
				$cartao_resposta = $this->container->get ( 'question.cartao_resposta.handler' )->put ( $cartao_resposta, $request->request->all () );
			}
			
			$routeOptions = array (
					'codigo' => $cartao_resposta->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_cartao_resposta', $routeOptions, $statusCode);
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
	public function patchCartaoRespostaAction(Request $request, $codigo) {
		try {			
			$cartao_resposta = $this->container->get ( 'question.cartao_resposta.handler' )->patch ( $this->getCartaoRespostaAction ( $codigo ), $request->request->all () );
			
			$routeOptions = array (
					'codigo' => $cartao_resposta->getCodigo (),
					'_format' => $request->get ( '_format' ) 
			);
			
			return $this->routeRedirectView ( 'aspe_get_cartao_resposta', $routeOptions, Codes::HTTP_OK );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
}