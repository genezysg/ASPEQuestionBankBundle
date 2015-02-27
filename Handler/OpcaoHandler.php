<?php
namespace QuestionBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use QuestionBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;
use EntityBundle\Entity\Opcao;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Extractor\Handler\FosRestHandler;
use QuestionBundle\Form\OpcaoType;

class OpcaoHandler{

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
	
	private function createOpcao()
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
		$opcao = $this->createOpcao();
		return $this->processForm($opcao, $parametros, 'POST');
	}

	public function put(Opcao $opcao, array $parametros)
	{		
		return $this->processForm($opcao, $parametros, 'PUT');
	}

	public function patch(Opcao $opcao, array $parametros)
	{
		return $this->processForm($opcao, $parametros, 'PATCH');
	}

	private function processForm(Opcao $opcao, array $parametros, $metodo = "PUT")
	{
		$form = $this->formFactory->create(new OpcaoType(), $opcao, array('method' => $metodo));
		$form->submit($parametros, 'PATCH' !== $metodo);
		if ($form->isValid()) {
	
			$opcao = $form->getData();
			$this->om->persist($opcao);
			$this->om->flush($opcao);
	
			return $opcao;
		}
	
		throw new InvalidFormException('Invalid submitted data', $form);
	}
	
	public function delete(Opcao $opcao)
	{
		return $this->processDelete($opcao);
	}
	
	private function processDelete(Opcao $opcao)
	{
		$this->om->remove($opcao);
		$this->om->flush($opcao);
	
		return $opcao;
	}

}