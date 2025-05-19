<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SupportTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users
        $users = User::where('role', '!=', 'admin')->take(5)->get();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Creating a test user.');
            $user = User::create([
                'name' => 'Test User',
                'email' => 'testuser@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $users = collect([$user]);
        }
        
        $statuses = [
            SupportTicket::STATUS_OPEN,
            SupportTicket::STATUS_IN_PROGRESS,
            SupportTicket::STATUS_WAITING_USER,
            SupportTicket::STATUS_CLOSED,
        ];
        
        $priorities = [
            SupportTicket::PRIORITY_LOW,
            SupportTicket::PRIORITY_MEDIUM,
            SupportTicket::PRIORITY_HIGH,
            SupportTicket::PRIORITY_URGENT,
        ];
        
        $categories = [
            SupportTicket::CATEGORY_GENERAL,
            SupportTicket::CATEGORY_ACCOUNT,
            SupportTicket::CATEGORY_ORDERS,
            SupportTicket::CATEGORY_PAYMENT,
            SupportTicket::CATEGORY_SUBSCRIPTION,
            SupportTicket::CATEGORY_TECHNICAL,
        ];
        
        $subjects = [
            'Problème avec ma commande #12345',
            'Comment modifier mon abonnement ?',
            'Je n\'ai pas reçu ma commande',
            'Question sur les tarifs',
            'Problème technique avec le site',
            'Comment annuler ma commande ?',
            'Problème de paiement',
            'Où est ma facture ?',
            'Comment contacter le service client ?',
            'Problème avec mon compte',
        ];
        
        $messages = [
            'Bonjour, j\'ai un problème avec ma commande. Pouvez-vous m\'aider ?',
            'Je n\'arrive pas à accéder à mon compte. Que dois-je faire ?',
            'Bonjour, je souhaiterais savoir comment modifier mon adresse de livraison.',
            'Je n\'ai pas reçu ma commande alors que le statut indique "livré". Que se passe-t-il ?',
            'Comment puis-je annuler mon abonnement ?',
            'Le site ne fonctionne pas correctement sur mon téléphone. Pouvez-vous m\'aider ?',
            'Je voudrais savoir si vous proposez des tarifs spéciaux pour les entreprises.',
            'Bonjour, j\'ai un problème avec le paiement de ma commande. La transaction a été refusée.',
            'Je n\'ai pas reçu ma facture par email. Pouvez-vous me l\'envoyer à nouveau ?',
            'Comment puis-je suivre ma commande ?',
        ];
        
        $adminResponses = [
            'Bonjour, merci pour votre message. Nous allons examiner votre problème et vous répondre dans les plus brefs délais.',
            'Bonjour, nous avons bien reçu votre demande. Pourriez-vous nous fournir plus de détails ?',
            'Bonjour, nous sommes désolés pour ce désagrément. Nous allons résoudre ce problème rapidement.',
            'Bonjour, votre demande a été prise en compte. Un conseiller va vous contacter prochainement.',
            'Bonjour, merci pour votre patience. Votre problème a été résolu.',
        ];
        
        $this->command->info('Creating support tickets...');
        
        // Create 10 tickets
        for ($i = 0; $i < 10; $i++) {
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            
            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'subject' => $subjects[array_rand($subjects)],
                'status' => $status,
                'priority' => $priorities[array_rand($priorities)],
                'category' => $categories[array_rand($categories)],
                'reference_number' => SupportTicket::generateReferenceNumber(),
                'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 24)),
                'closed_at' => $status === SupportTicket::STATUS_CLOSED ? now()->subDays(rand(0, 5)) : null,
            ]);
            
            // Create initial message from user
            SupportMessage::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'message' => $messages[array_rand($messages)],
                'is_from_admin' => false,
                'is_auto_reply' => false,
                'created_at' => $ticket->created_at,
            ]);
            
            // Add admin response for some tickets
            if (in_array($status, [SupportTicket::STATUS_IN_PROGRESS, SupportTicket::STATUS_WAITING_USER, SupportTicket::STATUS_CLOSED])) {
                // Find an admin user
                $admin = User::where('role', 'admin')->first();
                
                if (!$admin) {
                    $admin = User::create([
                        'name' => 'Admin User',
                        'email' => 'admin@example.com',
                        'password' => bcrypt('password'),
                        'email_verified_at' => now(),
                        'role' => 'admin',
                    ]);
                }
                
                SupportMessage::create([
                    'support_ticket_id' => $ticket->id,
                    'user_id' => $admin->id,
                    'message' => $adminResponses[array_rand($adminResponses)],
                    'is_from_admin' => true,
                    'is_auto_reply' => false,
                    'created_at' => $ticket->created_at->addHours(rand(1, 24)),
                ]);
            }
        }
        
        $this->command->info('Support tickets created successfully!');
    }
} 