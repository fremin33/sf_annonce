<?php
namespace ff\PlatformBundle\Antispam;

/**
 * Class Antispam
 *
 * @package ff\PlatformBundle\Antispam
 */
class ffAntispam {
    /**
     * Vérifie si le texte est un spam ou non
     * @param string $text
     * @return bool
     */
    private $mailer;
    private $locale;
    private $minLength;
    public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
    {
    $this->mailer = $mailer;
    $this->locale = $locale;
    $this->minLength = $minLength;
    }


    public function isSpam($text) {
        return strlen($text)<$this->minLength;
    }
}

