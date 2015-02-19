<?php
namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class QuestoesDaProva
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $codigo;
	/** 
	 * @ORM/ManyToOne(targetEntity="Prova")
	 */
	private $cod_prova;
	/**
	 * @ORM/ManyToOne(targetEntity="Questao")
	 */
	private $cod_questao;
	public function getCodigo() {
		return $this->codigo;
	}
	public function getCodProva() {
		return $this->cod_prova;
	}
	public function setCodProva($cod_prova) {
		$this->cod_prova = $cod_prova;
		return $this;
	}
	public function getCodQuestao() {
		return $this->cod_questao;
	}
	public function setCodQuestao($cod_questao) {
		$this->cod_questao = $cod_questao;
		return $this;
	}
}