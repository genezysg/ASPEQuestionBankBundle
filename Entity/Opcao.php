<?php
namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Opcao
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
	private $nome;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Questao")
	 * @ORM\JoinColumn(name="cod_questao", referencedColumnName="codigo")
	 */
	private $cod_questao;
	
	public function getCodigo() {
		return $this->codigo;
	}
	public function getNome() {
		return $this->nome;
	}
	public function setNome($nome) {
		$this->nome = $nome;
		return $this;
	}
	public function getCodQuestao() {
		return $this->cod_questao;
	}
	public function setCodQuestao($cod_questao) {
		$this->cod_questao = $cod_questao;
		return $this;
	}
	public function __toString(){
		return $this->nome;
	}
}