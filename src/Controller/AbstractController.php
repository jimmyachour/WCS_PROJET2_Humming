<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 15:38
 * PHP version 7
 */

namespace App\Controller;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 *
 */
abstract class AbstractController
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     *  Initializes this class.
     */
    public function __construct()
    {

        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => !APP_DEV,
                'debug' => APP_DEV,
            ]
        );
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addGlobal('openTime', $this->isOpen());
    }


    /**
     * Display item listing
     *
     * @return bool
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function isOpen(): bool
    {
        date_default_timezone_set('Europe/Paris');

        $heureOuverture = 7;
        $heureFermeture = 16;

        $heure = date('H');
        $jourActuel = date('w');

        // ouvert du lundi (1) au vendredi (5)
        // dimanche = 0
        // samedi = 6
        $planningDesFermetures = array(0, 6);

        $a = (($heure >= $heureOuverture) and ($heure <= $heureFermeture));

        $b = !in_array($jourActuel, $planningDesFermetures) ;

        return $a && $b;
    }
}
