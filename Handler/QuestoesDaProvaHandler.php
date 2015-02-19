<?php
namespace QuestionBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use QuestionBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;
use QuestionBundle\Entity\QuestoesDaProva;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Extractor\Handler\FosRestHandler;
use QuestionBundle\Form\QuestoesDaProvaType;

class QuestoesDaProvaHandler{

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
	
	private function createQuestoesDaProva()
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
		$questoes_prova = $this->createQuestoesDaProva();
		return $this->processForm($questoes_prova, $parametros, 'POST');
	}

	public function put(QuestoesDaProva $questoes_prova, array $parametros)
	{		
		return $this->processForm($questoes_prova, $parametros, 'PUT');
	}

	public function patch(QuestoesDaProva $questoes_prova, array $parametros)
	{
		return $this->processForm($questoes_prova, $parametros, 'PATCH');
	}

	private function processForm(QuestoesDaProva $questoes_prova, array $parametros, $metodo = "PUT")
	{
		$form = $this->formFactory->create(new QuestoesDaProvaType(), $questoes_prova, array('method' => $metodo));
		$form->submit($parametros, 'PATCH' !== $metodo);
		if ($form->isValid()) {
	
			$questoes_prova = $form->getData();
			$this->om->persist($questoes_prova);
			$this->om->flush($questoes_prova);
	
			return $questoes_prova;
		}
	
		throw new InvalidFormException('Invalid submitted data', $form);
	}
	
	public function delete(QuestoesDaProva $questoes_prova)
	{
		return $this->processDelete($questoes_prova);
	}
	
	private function processDelete(QuestoesDaProva $questoes_prova)
	{
		$this->om->remove($questoes_prova);
		$this->om->flush($questoes_prova);
	
		return $questoes_prova;
	}

}