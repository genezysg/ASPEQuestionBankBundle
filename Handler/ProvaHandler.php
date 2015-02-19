<?php
namespace QuestionBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use QuestionBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;
use QuestionBundle\Entity\Prova;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Extractor\Handler\FosRestHandler;
use QuestionBundle\Form\ProvaType;

class ProvaHandler{

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
	
	private function createProva()
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
		$prova = $this->createProva();
		return $this->processForm($prova, $parametros, 'POST');
	}

	public function put(Prova $prova, array $parametros)
	{		
		return $this->processForm($prova, $parametros, 'PUT');
	}

	public function patch(Prova $prova, array $parametros)
	{
		return $this->processForm($prova, $parametros, 'PATCH');
	}

	private function processForm(Prova $prova, array $parametros, $metodo = "PUT")
	{
		$form = $this->formFactory->create(new ProvaType(), $prova, array('method' => $metodo));
		$form->submit($parametros, 'PATCH' !== $metodo);
		if ($form->isValid()) {
	
			$prova = $form->getData();
			$this->om->persist($prova);
			$this->om->flush($prova);
	
			return $prova;
		}
	
		throw new InvalidFormException('Invalid submitted data', $form);
	}
	
	public function delete(Prova $prova)
	{
		return $this->processDelete($prova);
	}
	
	private function processDelete(Prova $prova)
	{
		$this->om->remove($prova);
		$this->om->flush($prova);
	
		return $prova;
	}

}