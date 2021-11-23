<?php
namespace App\Views;

use Bubu\ExtendHtmlTags\ExtendHtmlTags;
use Bubu\Http\Reponse\Reponse;

class Page
{

    public string  $pageContent;
    public ?int    $pageCode    = null;
    public mixed   $pageMessage = null;

    /**
     * show page
     * 
     * @param string $page
     * @param int|null $code
     * @param string|null $message
     * 
     * @return never
     */
    public function show(string $page, Reponse $reponse = null)
    {

        if (is_null($reponse)) $reponse = (new Reponse)->reponse200();

        $this->pageContent = file_get_contents("templates/{$page}.bubu.php", true);
        $this->pageContent = ExtendHtmlTags::create($this)->pageContent;

        $message = $this->pageMessage ?? $reponse->getHttpCode();
        $code    = $this->pageCode ?? $reponse->getHttpMessage();

        ob_start();
        echo eval('?>' . $this->pageContent);
        $reponse->setup();
        $reponse->setContent(ob_get_clean());
        $reponse->send();
    }

    /**
     * pageMessage
     *
     * @param  mixed $pageMessage
     * @return self
     */
    public function pageMessage(mixed $pageMessage): self
    {
        $this->pageMessage = $pageMessage;
        return $this;
    }

    /**
     * pageCode
     *
     * @param  int $pageCode
     * @return self
     */
    public function pageCode(mixed $pageCode): self
    {
        $this->pageCode = $pageCode;
        return $this;
    }
}
