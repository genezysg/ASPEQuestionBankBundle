<?php
namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class AutorDaQuestao
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $codigo;

	/**
	 * @ORM\Column(length=10)
	 */
	private $sigla;
	
	/**
	 * @ORM\Column(length=45)
	 */
	private $nome;
	/**
	 * @ORM\Column(type="integer") 
	 */
	private $ano;
	/**
	 * @ORM\OneToOne(targetEntity="Questao")
	 * @ORM\JoinColumn(name="cod_questao", referencedColumnName="codigo")
	 */
	private $questao;
	
	public function getCodigo() {
		return $this->codigo;
	}
	public function getSigla() {
		return $this->sigla;
	}
	public function setSigla($sigla) {
		$this->sigla = $sigla;
		return $this;
	}
	public function getNome() {
		return $this->nome;
	}
	public function setNome($nome) {
		$this->nome = $nome;
		return $this;
	}
	public function getAno() {
		return $this->ano;
	}
	public function setAno($ano) {
		$this->ano = $ano;
		return $this;
	}
	public function getQuestao() {
		return $this->questao;
	}
	public function setQuestao($questao) {
		$this->questao = $questao;
		return $this;
	}
}