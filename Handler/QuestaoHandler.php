<?php
namespace QuestionBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use QuestionBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;
use EntityBundle\Entity\Questao;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Extractor\Handler\FosRestHandler;
use QuestionBundle\Form\QuestaoType;

class QuestaoHandler{

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
	
	private function createQuestao()
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
		$questao = $this->createQuestao();
		return $this->processForm($questao, $parametros, 'POST');
	}

	public function put(Questao $questao, array $parametros)
	{		
		return $this->processForm($questao, $parametros, 'PUT');
	}

	public function patch(Questao $questao, array $parametros)
	{
		return $this->processForm($questao, $parametros, 'PATCH');
	}

	private function processForm(Questao $questao, array $parametros, $metodo = "PUT")
	{
		$form = $this->formFactory->create(new QuestaoType(), $questao, array('method' => $metodo));
		$form->submit($parametros, 'PATCH' !== $metodo);
		if ($form->isValid()) {
	
			$questao = $form->getData();
			$this->om->persist($questao);
			$this->om->flush($questao);
	
			return $questao;
		}
	
		throw new InvalidFormException('Invalid submitted data', $form);
	}
	
	public function delete(Questao $questao)
	{
		return $this->processDelete($questao);
	}
	
	private function processDelete(Questao $questao)
	{
		$this->om->remove($questao);
		$this->om->flush($questao);
	
		return $questao;
	}

}