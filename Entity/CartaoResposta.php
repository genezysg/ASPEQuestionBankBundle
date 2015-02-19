<?php
namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class CartaoResposta
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $codigo;
	
	/**
	 * @ORM\Column(length=25)
	 */
	private $matriculaAluno;
	/** 
	 * @ORM\OneToOne(targetEntity="QuestoesDaProva")
	 * @ORM\JoinColumn(name="cod_questao_prova", referencedColumnName="codigo")
	 */
	private $cod_questao_prova;
	/**
	 * @ORM\OneToOne(targetEntity="Opcao")
	 * @ORM\JoinColumn(name="resposta", referencedColumnName="codigo")
	 */
	private $resposta;
	public function getCodigo() {
		return $this->codigo;
	}
	public function getMatriculaAluno() {
		return $this->matriculaAluno;
	}
	public function setMatriculaAluno($matriculaAluno) {
		$this->matriculaAluno = $matriculaAluno;
		return $this;
	}
	public function getCodQuestaoProva() {
		return $this->cod_questao_prova;
	}
	public function setCodQuestaoProva($cod_questao_prova) {
		$this->cod_questao_prova = $cod_questao_prova;
		return $this;
	}
	public function getResposta() {
		return $this->resposta;
	}
	public function setResposta($resposta) {
		$this->resposta = $resposta;
		return $this;
	}
}