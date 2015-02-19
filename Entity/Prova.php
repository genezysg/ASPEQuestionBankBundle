<?php
namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Prova
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
	private $descricao;
	/**
	 * @ORM\Column(type="integer")
	 */
	private $semestre;
	/**
	 * @ORM\Column(type="integer")
	 */
	private $ano;
	/**
	 * @ORM\Column(type="integer")
	 */
	private $num_questoes;
	public function getCodigo() {
		return $this->codigo;
	}
	public function getDescricao() {
		return $this->descricao;
	}
	public function setDescricao($descricao) {
		$this->descricao = $descricao;
		return $this;
	}
	public function getSemestre() {
		return $this->semestre;
	}
	public function setSemestre($semestre) {
		$this->semestre = $semestre;
		return $this;
	}
	public function getAno() {
		return $this->ano;
	}
	public function setAno($ano) {
		$this->ano = $ano;
		return $this;
	}
	public function getNumQuestoes() {
		return $this->num_questoes;
	}
	public function setNumQuestoes($num_questoes) {
		$this->num_questoes = $num_questoes;
		return $this;
	}
		
	
}