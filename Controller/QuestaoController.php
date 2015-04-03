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

class QuestaoController extends FOSRestController {
	/**
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
	 * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")
	 */
	public function getQuestaosAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		if (! ($questoes = $this->container->get ( 'question.questao.handler' )->all ( $limite, $posicao_inicio )))
			throw new NotFoundHttpException ( sprintf ( 'Recurso não encontrado.' ) );
		else
			return $questoes;
	}
	public function getQuestaoAction($codigo) {
		if (! ($questao = $this->container->get ( 'question.questao.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'O recurso \'%s\' não foi encontrado.', $codigo ) );
		}
		return $questao;
	}
	public function postQuestaoAction(Request $request) {
		try {
			$this->container->get ( 'question.questao.handler' )->post ( $request->request->all () );
			$statusCode = Codes::HTTP_CREATED;
			return new Response ( null, $statusCode );
		} catch ( InvalidFormException $exception ) {
			return $exception->getForm ();
		}
	}
	public function deleteQuestaoAction($codigo, Request $request, ParamFetcherInterface $paramFetcher) {
		try {
			if ($questao = $this->container->get ( 'question.questao.handler' )->get ( $codigo )) {
				$statusCode = Codes::HTTP_OK;
				$this->container->get ( 'question.questao.handler' )->delete ( $questao );
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
	public function putQuestaoAction(Request $request, $codigo) {
		try {
			if (! ($questao = $this->container->get ( 'question.questao.handler' )->get ( $codigo ))) {
				$questao = $this->container->get ( 'question.questao.handler' )->post ( $request->request->all () );
			} else {
				$questao = $this->container->get ( 'question.questao.handler' )->put ( $questao, $request->request->all () );
			}
			$statusCode = Codes::HTTP_OK;
			return new Response ( null, $statusCode );
		} catch ( InvalidFormException $exception ) {
			return $exception->getForm ();
		}
	}
	public function patchQuestaoAction(Request $request, $codigo) {
		try {
			if (! ($this->container->get ( 'question.questao.handler' )->get ( $codigo ))) {
				$this->container->get ( 'question.questao.handler' )->patch ( $this->getQuestaoAction ( $codigo ), $request->request->all () );
				$statusCode = Codes::HTTP_OK;
			} else
				$statusCode = Codes::HTTP_NOT_FOUND;
			
			return new Response ( null, $statusCode );
		} catch ( InvalidFormException $exception ) {
			
			return $exception->getForm ();
		}
	}
}