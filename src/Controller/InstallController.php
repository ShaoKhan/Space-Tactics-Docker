<?php

namespace App\Controller;

use App\Entity\Uni;
use App\Entity\User;
use App\Entity\Planet;
use App\Entity\Building;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * ACHTUNG, NOCHT KOMPLETT BAUSTELLE */

class InstallController extends AbstractController
{
    #[Route('/install', name: 'app_install')]
    public function install(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        if (!$this->isInstallationAllowed()) {
            return new Response('Installation ist nicht erlaubt oder wurde bereits durchgeführt!');
        }

        if ($request->isMethod('POST')) {
            $errors = $this->validateInput($request);
            
            if (empty($errors)) {
                // Universum erstellen
                $uni = new Uni();
                $uni->setName($request->request->get('uni_name', 'Mein Universum'));
                $uni->setGameSpeed(1);
                $uni->setFleetSpeed(1);
                $uni->setProductionSpeed(1);
                $uni->setStartMetal(500);
                $uni->setStartCrystal(500);
                $uni->setStartDeuterium(0);
                
                $entityManager->persist($uni);

                // Admin-Benutzer erstellen
                $user = new User();
                $user->setEmail($request->request->get('admin_email'));
                $user->setRoles(['ROLE_ADMIN']);
                $user->setUni($uni);
                $user->setUsername($request->request->get('admin_username'));
                
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $request->request->get('admin_password')
                );
                $user->setPassword($hashedPassword);
                
                $entityManager->persist($user);

                // Startplanet erstellen
                $planet = new Planet();
                $planet->setName('Hauptplanet');
                $planet->setUser($user);
                $planet->setUni($uni);
                $planet->setGalaxy(1);
                $planet->setSystem(1);
                $planet->setPosition(1);
                $planet->setMetal($uni->getStartMetal());
                $planet->setCrystal($uni->getStartCrystal());
                $planet->setDeuterium($uni->getStartDeuterium());
                
                $entityManager->persist($planet);

                // Grundgebäude erstellen
                $buildings = [
                    'metal_building' => 1,
                    'crystal_building' => 1,
                    'deuterium_building' => 1
                ];

                foreach ($buildings as $type => $level) {
                    $building = new Building();
                    $building->setPlanet($planet);
                    $building->setType($type);
                    $building->setLevel($level);
                    $building->setLastUpdate(new \DateTime());
                    
                    $entityManager->persist($building);
                }

                $entityManager->flush();
                $this->markAsInstalled();

                return new Response('Installation erfolgreich abgeschlossen!');
            }

            return new Response('Fehler bei der Installation: ' . implode(', ', $errors));
        }

        return $this->render('install/index.html.twig');
    }

    private function validateInput(Request $request): array
    {
        $errors = [];
        
        if (strlen($request->request->get('uni_name')) < 3) {
            $errors[] = 'Der Universumname muss mindestens 3 Zeichen lang sein.';
        }
        
        if (!filter_var($request->request->get('admin_email'), FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
        }
        
        if (strlen($request->request->get('admin_password')) < 3) {
            $errors[] = 'Das Passwort muss mindestens 8 Zeichen lang sein.';
        }

        if (strlen($request->request->get('admin_username')) < 3) {
            $errors[] = 'Der Benutzername muss mindestens 3 Zeichen lang sein.';
        }
        
        return $errors;
    }

    // Zusätzliche Sicherheitsüberprüfungen im Controller
    private function isInstallationAllowed(): bool
    {
        // Prüfe ob Installation bereits durchgeführt wurde
        if (file_exists(__DIR__ . '/../../var/installed.lock')) {
            return false;
        }

        // Prüfe IP-Adresse (optional)
        if (!in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
            return false;
        }

        return true;
    }

    // Nach erfolgreicher Installation
    private function markAsInstalled(): void
    {
        file_put_contents(__DIR__ . '/../../var/installed.lock', date('Y-m-d H:i:s'));
    }
}