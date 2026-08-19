<?php

declare(strict_types=1);

namespace Src\App\Http\Controllers;

use Src\App\Services\IServices\IAuditoriaService;
use Src\App\Services\IServices\IIntegranteService;
use Src\App\Utils\View;

class HomeController extends SharedController
{
    private int $limit = 5;
    public function __construct(
        private IAuditoriaService $auditoriaService,
        private IIntegranteService $integranteService
    ) {
    }
    public function index(): string
    {
        $content = View::render('Home/index', [
            'nome' => 'Ola, ' . $_SESSION['usuario'],
            'countIntegrante' => $this->integranteService->count(),
            'lastRegistros' => $this->auditoriaService->getLastRegistros($this->limit),
        ]);

        return parent::getPage('PROJETO - Dashboard', $content, [
            'showSidebar' => true,
            'activePage' => 'dashboard',
        ]);
    }
}
