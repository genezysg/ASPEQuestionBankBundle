<?php
namespace QuestionBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use QuestionBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;
use QuestionBundle\Entity\CartaoResposta;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Extractor\Handler\FosRestHandler;
use QuestionBundle\Form\CartaoRespostaType;

class CartaoRespostaHandler{

	private $om;
	private $entityClass;
	private $repository;
	private $formFactory;
	
	
	public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
	{
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
		$this->formFactory = $formFactory;
	}
	
	private function createCartaoResposta()
	{
		return new $this->entityClass();
	}
	
	public function get($codigo) 
	{
		return $this->repository->find($codigo);
	}

	public function all($limite = 5, $posicao_inicio = 0)
	{
		return $this->repository->findBy(array(), null, $limite, $posicao_inicio);
	}
	
	public function post(array $parametros) 
	{
		$cartao_resposta = $this->createCartaoResposta();
		return $this->processForm($cartao_resposta, $parametros, 'POST');
	}

	public function put(CartaoResposta $cartao_resposta, array $parametros)
	{		
		return $this->processForm($cartao_resposta, $parametros, 'PUT');
	}

	public function patch(CartaoResposta $cartao_resposta, array $parametros)
	{
		return $this->processForm($cartao_resposta, $parametros, 'PATCH');
	}

	private function processForm(CartaoResposta $cartao_resposta, array $parametros, $metodo = "PUT")
	{
		$form = $this->formFactory->create(new CartaoRespostaType(), $cartao_resposta, array('method' => $metodo));
		$form->submit($parametros, 'PATCH' !== $metodo);
		if ($form->isValid()) {
	
			$cartao_resposta = $form->getData();
			$this->om->persist($cartao_resposta);
			$this->om->flush($cartao_resposta);
	
			return $cartao_resposta;
		}
	
		throw new InvalidFormException('Invalid submitted data', $form);
	}
	
	public function delete(CartaoResposta $cartao_resposta)
	{
		return $this->processDelete($cartao_resposta);
	}
	
	private function processDelete(CartaoResposta $cartao_resposta)
	{
		$this->om->remove($cartao_resposta);
		$this->om->flush($cartao_resposta);
	
		return $cartao_resposta;
	}

}