<?php
namespace QuestionBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use QuestionBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;
use EntityBundle\Entity\AutorDaQuestao;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Extractor\Handler\FosRestHandler;
use QuestionBundle\Form\AutorDaQuestaoType;

class AutorDaQuestaoHandler{

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
	
	private function createAutorDaQuestao()
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
		$autor_questao = $this->createAutorDaQuestao();
		return $this->processForm($autor_questao, $parametros, 'POST');
	}

	public function put(AutorDaQuestao $autor_questao, array $parametros)
	{		
		return $this->processForm($autor_questao, $parametros, 'PUT');
	}

	public function patch(AutorDaQuestao $autor_questao, array $parametros)
	{
		return $this->processForm($autor_questao, $parametros, 'PATCH');
	}

	private function processForm(AutorDaQuestao $autor_questao, array $parametros, $metodo = "PUT")
	{
		$form = $this->formFactory->create(new AutorDaQuestaoType(), $autor_questao, array('method' => $metodo));
		$form->submit($parametros, 'PATCH' !== $metodo);
		if ($form->isValid()) {
	
			$autor_questao = $form->getData();
			$this->om->persist($autor_questao);
			$this->om->flush($autor_questao);
	
			return $autor_questao;
		}
	
		throw new InvalidFormException('Invalid submitted data', $form);
	}
	
	public function delete(AutorDaQuestao $autor_questao)
	{
		return $this->processDelete($autor_questao);
	}
	
	private function processDelete(AutorDaQuestao $autor_questao)
	{
		$this->om->remove($autor_questao);
		$this->om->flush($autor_questao);
	
		return $autor_questao;
	}

}