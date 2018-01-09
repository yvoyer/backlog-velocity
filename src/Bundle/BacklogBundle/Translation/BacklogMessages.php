<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Translation;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

final class BacklogMessages
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FlashBagInterface
     */
    private $flashes;

    /**
     * @param TranslatorInterface $translator
     * @param FlashBagInterface $flashes
     */
    public function __construct(TranslatorInterface $translator, FlashBagInterface $flashes)
    {
        $this->translator = $translator;
        $this->flashes = $flashes;
    }

    /**
     * @param string $message
     * @param array $parameters
     */
    public function addSuccess(string $message, array $parameters = []) :void {
        $this->flashes->add('success', $this->translator->trans($message, $parameters));
    }

    /**
     * @param string $message
     * @param array $parameters
     */
    public function addInfo(string $message, array $parameters = []) :void {
        $this->flashes->add('info', $this->translator->trans($message, $parameters));
    }

    /**
     * @param string $message
     * @param array $parameters
     */
    public function addWarning(string $message, array $parameters = []) :void {
        $this->flashes->add('warning', $this->translator->trans($message, $parameters));
    }

    /**
     * @param string $message
     * @param array $parameters
     */
    public function addError(string $message, array $parameters = []) :void {
        $this->flashes->add('danger', $this->translator->trans($message, $parameters));
    }

    public function message(string $key, array $arguments = []) :string
    {
       // var_dump($this->translator);
        return $this->translator->trans($key, $arguments, 'messages');
    }

    public static function fixture() :self
    {
        $translator = new Translator('en');
        $loader = new YamlFileLoader();
        $loader->load($resource = __DIR__ . '/../Resources/translations/messages.en.yml', 'en');
        $translator->addLoader('yml', $loader);
        $translator->addResource('yml', $resource, 'en');

        return new self($translator, new FlashBag());
    }
}
