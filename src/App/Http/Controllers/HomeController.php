<?php

declare(strict_types=1);

namespace Src\App\Http\Controllers;

use Src\App\Services\IServices\IAuditoriaService;
use Src\App\Services\IServices\IExemploService;
use Src\App\Utils\View;

class HomeController extends SharedController
{
    private int $limit = 5;
    public function __construct(
        private IAuditoriaService $auditoriaService,
        private IExemploService $exemploService
    ) {
    }
    public function index(): string
    {
        $content = View::render('Home/index', [
            'nome' => 'Ola, ' . $_SESSION['usuario'],
            'countExemplo' => $this->exemploService->count(),
            'lastRegistros' => $this->auditoriaService->getLastRegistros($this->limit),
        ]);

        return parent::getPage('PROJETO - Dashboard', $content, [
            'showSidebar' => true,
            'activePage' => 'dashboard',
        ]);
    }
}
