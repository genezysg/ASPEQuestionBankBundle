<?php
namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Questao
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
	private $enunciado;
	/** 
	 * @ORM\OneToOne(targetEntity="Opcao")
	 * @ORM\JoinColumn(name="resposta", referencedColumnName="codigo")
	 */
	private $resposta;
	/**
	 * @ORM\Column(length=15)
	 */
	private $siglaDisciplina;	
	public function getCodigo() {
		return $this->codigo;
	}
	public function getEnunciado() {
		return $this->enunciado;
	}
	public function setEnunciado($enunciado) {
		$this->enunciado = $enunciado;
		return $this;
	}
	public function getResposta() {
		return $this->resposta;
	}
	public function setResposta($resposta) {
		$this->resposta = $resposta;
		return $this;
	}
	public function getSiglaDisciplina() {
		return $this->siglaDisciplina;
	}
	public function setSiglaDisciplina($siglaDisciplina) {
		$this->siglaDisciplina = $siglaDisciplina;
		return $this;
	}
	public function __toString(){
		return $this->enunciado;
	}
}