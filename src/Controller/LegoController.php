<?php

/* indique où "vit" ce fichier */
namespace App\Controller;

/* indique l'utilisation du bon bundle pour gérer nos routes */
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Lego;
use App\Service\CreditsGenerator;

/* le nom de la classe doit être cohérent avec le nom du fichier */
class LegoController extends AbstractController
{
    private $legos;

    public function __construct()
    {
        $data = file_get_contents(__DIR__ . '/../data.json');
        $legoData = json_decode($data, true);
        
        $this->legos = [];
        
        foreach ($legoData as $item) {
            $lego = new Lego( $item['collection'], $item['id'], $item['name']);

            $lego->setDescription($item['description']);
            $lego->setPrice($item['price']);
            $lego->setPieces($item['pieces']);
            $lego->setBoxImage($item['images']['box']);
            $lego->setLegoImage($item['images']['bg']);
            
            $this->legos[] = $lego;
        }
    }

    // L’attribute #[Route] indique ici que l'on associe la route
    // "/" à la méthode home pour que Symfony l'exécute chaque fois
    // que l'on accède à la racine de notre site.
    #[Route('/')]
    public function home(): Response
    {

      
        return $this->render('lego.html.twig', ['legos' => $this->legos]);
    }
    
    // #[Route('/me')]
    // public function me()
    // {
    //     die("Léopold");
    // }

    #[Route('/{collection}', name : 'filter_by_collection', requirements: ['collection' => 'creator|star_wars|creator_expert'])]
public function filter($collection): Response
{
    $filter = array_filter($this->legos, function($legoitem) use ($collection) {
        // replace space by _ and caps by lowercase
        $collection = ucwords(str_replace('_', ' ', $collection));
    return $legoitem->getCollection() == $collection;
});

    return $this->render('lego.html.twig', ['legos' => $filter]);

}

#[Route('/credits', 'credits')]
public function credits(CreditsGenerator $credits): Response
{
    return new Response($credits->getCredits());
}




}

