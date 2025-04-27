<?php

declare(strict_types=1);

namespace Core\Controller;
use Core\Logger;

/**
 *
 * Cette permet de gérer les erreurs (http, Exception, ...etc) etrend la page
 */
class ErrorController extends AbstractController
{
    /**
     * Gère les erreurs HTTP
     * @param string $module back ou front
     * @param int $code ex: 404
     * @throws \Exception
     * @return void
     */
    public function httpError(string $module, int $code)
    {
        $logger = new Logger();
        $logger->error(sprintf(
            'Erreur HTTP %d dans le module %s',
            $code,
            $module
        ));
        

        http_response_code($code);


        switch ($code) {
            case 404:
                $page = '404';
                break;
            default:
                throw new \Exception('Une erreur a été rencontré');
        }

        $this->render('/' . $module . '/error/' . $page . '.php');
    }


    /**
     * Gère les exceptions
     * @param \Throwable $e
     * @return void
     */
    public function exceptionError(\Throwable $e)
    {
        $logger = new Logger();
        $logger->error(sprintf(
            '%s in %s on line %d',
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ));

        if (DEV) {
            $this->render('error/exception', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        } else {
            $this->render('error/fatal', [
                'message' => 'Une erreur technique est survenue. Merci de réessayer plus tard.',
                'file' => '',
                'line' => '',
            ]);
        }
    }


    /**
     * Gère les erreurs fatales
     * @param array $error
     * @return void
     */
    public function fatalError(array $error)
    {
        $logger = new Logger();
        $logger->error(sprintf(
            '%s in %s on line %d',
            $error['message'],
            $error['file'],
            $error['line']
        ));
        $this->render('/error/fatal.php', [
            'message' => $error['message'],
            'file' => $error['file'],
            'line' => $error['line'],
        ]);
    }
}
