<?php

/* indique où "vit" ce fichier */
namespace App\Controller;

/* indique l'utilisation du bon bundle pour gérer nos routes */
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CreditsGenerator;
use App\Repository\LegoRepository;
use App\Entity\LegoCollection;
use App\Repository\LegoCollectionRepository;

/* le nom de la classe doit être cohérent avec le nom du fichier */
class LegoController extends AbstractController
{
    private $legoRepository;

    public function __construct(LegoRepository $legoRepository)
    {
        $this->legoRepository = $legoRepository;
    }

    // L’attribute #[Route] indique ici que l'on associe la route
    // "/" à la méthode home pour que Symfony l'exécute chaque fois
    // que l'on accède à la racine de notre site.
    #[Route('/', name: 'home')]
    public function home(LegoCollectionRepository $CollectionRepository): Response
    {
        $legos = $this->legoRepository->findAll();
        $collections = $CollectionRepository->findAll();
        return $this->render('lego.html.twig', ['legos' => $legos, 'collections' => $collections]);
    }

    #[Route('/lego', name: 'lego')]
    public function lego(): Response
    {
// use doctrine
        $legos = $this->legoRepository->findAll();
        return $this->render('lego.html.twig', ['legos' => $legos]);
    }
    


//     #[Route('/{collection}', name : 'filter_by_collection', requirements: ['collection' => 'creator|star_wars|creator_expert'])]
// public function filter($collection): Response
// {
// $legos = $this->legoRepository->findAll();
// $collectionFormated = ucwords(str_replace('_', ' ', $collection));
    

// $filteredLegos = array_filter($legos, function ($lego) use ($collectionFormated) {
//     return $lego->getCollection() === $collectionFormated;
// });

// return $this->render('lego.html.twig', ['legos' => $filteredLegos, 'collection' => $collectionFormated]);

// }

#[Route('/credits', 'credits')]
public function credits(CreditsGenerator $credits): Response
{
    return new Response($credits->generate());
}

#[Route('/{name}', name: 'collection')]
public function collection(LegoCollection $collection,LegoCollectionRepository $CollectionRepository): Response
{
    $collections = $CollectionRepository->findAll();
$legos =$collection->getLegos();    
return $this->render('lego.html.twig', ['legos' => $legos, 'collections' => $collections]);
}






// #[Route('/test')]
// public function createProduct(EntityManagerInterface $entityManager): Response
// {

//     $l = new Lego(1234);
//     $l->setName('Super Lego');
//     $l->setCollection('Star Wars');
//     $l->setDescription('This is a super Lego');
//     $l->setPrice(99.99);
//     $l->setPieces(1000);
//     $l->setBoxImage('super_lego.jpg');
//     $l->setLegoImage('super_lego_bg.jpg');

//     $entityManager->persist($l); // Persister l'objet
//     $entityManager->flush(); // Enregistrer dans la base de données

//     return new Response('Saved new product with id '.$l->getId());
// }
}