<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return new Response(
            <<<EOF
<html>
    <body style="
        background-image: url('/images/under-construction.png'); 
        background-position: center;
        background-repeat: no-repeat;
    ">
    </body>
</html>
EOF
        );
    }
}
