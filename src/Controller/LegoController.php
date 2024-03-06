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
use App\Service\DatabaseInterface;
use Doctrine\ORM\EntityManagerInterface;

/* le nom de la classe doit être cohérent avec le nom du fichier */
class LegoController extends AbstractController
{
    private $legos;

    public function __construct()
    {
        $db = new DatabaseInterface();
        $data = $db->getAllLegos();
        $data = json_encode($data);
        $legoData = json_decode($data, true);
        
        $this->legos = [];
        
        foreach ($legoData as $item) {
            $lego = new Lego( $item['collection'], $item['id'], $item['name']);

            $lego->setDescription($item['description']);
            $lego->setPrice($item['price']);
            $lego->setPieces($item['pieces']);
            // $lego->setBoxImage($item['images']['box']);
            // $lego->setLegoImage($item['images']['bg']);
            
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
    return new Response($credits->generate());
}


// use getAllLegos method from DatabaseInterface
#[Route('/legos')]
public function legos(DatabaseInterface $database): Response
{
    $legos = $database->getAllLegos();
    
    return $this->render('lego.html.twig', ['legos' => $legos]);

}

#[Route('/test')]
public function createProduct(EntityManagerInterface $entityManager): Response
{

    $l = new Lego(1234);
    $l->setName('Super Lego');
    $l->setCollection('Star Wars');
    $l->setDescription('This is a super Lego');
    $l->setPrice(99.99);
    $l->setPieces(1000);
    $l->setBoxImage('super_lego.jpg');
    $l->setLegoImage('super_lego_bg.jpg');

    $entityManager->persist($l); // Persister l'objet
    $entityManager->flush(); // Enregistrer dans la base de données

    return new Response('Saved new product with id '.$l->getId());
}
}