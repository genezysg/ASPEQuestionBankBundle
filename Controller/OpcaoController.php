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
use Symfony\Component\HttpFoundation\Response;

class OpcaoController extends FOSRestController {
	/**
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
	 * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")
	 */
	public function getOpcaosAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		if (! ($opcoes = $this->container->get ( 'question.opcao.handler' )->all ( $limite, $posicao_inicio )))
			throw new NotFoundHttpException ( sprintf ( 'Recurso não encontrado.' ) );
		else
			return $opcoes;
	}
	public function getOpcaoAction($codigo) {
		if (! ($opcao = $this->container->get ( 'question.opcao.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'O recurso \'%s\' não foi encontrado.', $codigo ) );
		}
		return $opcao;
	}
	public function postOpcaoAction(Request $request) {
		try {
			$this->container->get ( 'question.opcao.handler' )->post ( $request->request->all () );
			$statusCode = Codes::HTTP_CREATED;
			return new Response ( null, $statusCode );
		} catch ( InvalidFormException $exception ) {
			return $exception->getForm ();
		}
	}
	public function deleteOpcaoAction($codigo, Request $request, ParamFetcherInterface $paramFetcher) {
		try {
			if ($opcao = $this->container->get ( 'question.opcao.handler' )->get ( $codigo )) {
				$statusCode = Codes::HTTP_OK;
				$this->container->get ( 'question.opcao.handler' )->delete ( $opcao );
			} else
				$statusCode = Codes::HTTP_NOT_FOUND;
			return new Response ( null, $statusCode );
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
			} else {
				$opcao = $this->container->get ( 'question.opcao.handler' )->put ( $opcao, $request->request->all () );
			}
			$statusCode = Codes::HTTP_OK;
			return new Response ( null, $statusCode );
		} catch ( InvalidFormException $exception ) {
			return $exception->getForm ();
		}
	}
	public function patchOpcaoAction(Request $request, $codigo) {
		try {
			if (! ($this->container->get ( 'question.opcao.handler' )->get ( $codigo ))) {
				$this->container->get ( 'question.opcao.handler' )->patch ( $this->getOpcaoAction ( $codigo ), $request->request->all () );
				$statusCode = Codes::HTTP_OK;
			} else
				$statusCode = Codes::HTTP_NOT_FOUND;
			
			return new Response ( null, $statusCode );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
}