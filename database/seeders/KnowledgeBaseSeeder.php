<?php

namespace Database\Seeders;

use App\Models\KnowledgeBaseCategory;
use App\Models\KnowledgeBaseArticle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KnowledgeBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin users for article authors
        $authors = User::role('admin')->get();
        
        if ($authors->isEmpty()) {
            // If no admin users, use any user
            $authors = User::take(3)->get();
            
            if ($authors->isEmpty()) {
                // Create a test user if no users exist
                $authors = [
                    User::create([
                        'name' => 'Admin User',
                        'email' => 'admin@example.com',
                        'password' => bcrypt('password'),
                    ])
                ];
                
                // Assign admin role to the user
                $authors[0]->assignRole('admin');
            }
        }
        
        // Create categories (merged with former subcategories)
        $categories = [
            [
                'name' => 'Getting Started',
                'description' => 'Basic information to help you get started with our platform',
                'order' => 1,
            ],
            [
                'name' => 'Account Management',
                'description' => 'Learn how to manage your account settings and preferences',
                'order' => 2,
            ],
            [
                'name' => 'Security Settings',
                'description' => 'Password and security options for your account',
                'order' => 3,
            ],
            [
                'name' => 'Registration',
                'description' => 'How to create and set up your account',
                'order' => 4,
            ],
            [
                'name' => 'Troubleshooting',
                'description' => 'Solutions to common problems you might encounter',
                'order' => 5,
            ],
            [
                'name' => 'Common Errors',
                'description' => 'Explanations and solutions for frequently encountered errors',
                'order' => 6,
            ],
            [
                'name' => 'Best Practices',
                'description' => 'Tips and recommendations for getting the most out of our platform',
                'order' => 7,
            ],
        ];

        $createdCategories = [];
        
        // Create categories
        foreach ($categories as $category) {
            $slug = Str::slug($category['name']);
            $createdCategories[] = KnowledgeBaseCategory::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'slug' => $slug,
                'order' => $category['order'],
            ]);
        }
        
        // All articles (consolidated)
        $articles = [
            [
                'title' => 'Welcome to the Help Desk',
                'content' => '<h2>Welcome to our Help Desk System</h2><p>This knowledge base provides information on how to use our help desk system efficiently. Here you\'ll find guides, tutorials, and answers to common questions.</p><p>Use the navigation menu to browse categories or use the search function to find specific information.</p>',
                'excerpt' => 'Introduction to our help desk system and how to use this knowledge base.',
                'category' => 'Getting Started',
                'is_published' => true,
                'featured' => true,
            ],
            [
                'title' => 'How to Change Your Password',
                'content' => '<h2>Changing Your Password</h2><p>Keeping your account secure is important. Follow these steps to change your password:</p><ol><li>Log in to your account</li><li>Go to Profile Settings</li><li>Select the "Security" tab</li><li>Click "Change Password"</li><li>Enter your current password</li><li>Enter and confirm your new password</li><li>Click "Save Changes"</li></ol><p>Your password should be at least 8 characters long and include uppercase letters, lowercase letters, numbers, and special characters for maximum security.</p>',
                'excerpt' => 'Step-by-step guide to changing your account password.',
                'category' => 'Account Management',
                'is_published' => true,
                'featured' => false,
            ],
            [
                'title' => 'Can\'t Login: Solutions to Common Login Problems',
                'content' => '<h2>Common Login Issues and Solutions</h2><p>Having trouble logging in? Here are solutions to the most common login problems:</p><h3>Forgotten Password</h3><p>Click the "Forgot Password" link on the login page to reset your password.</p><h3>Account Locked</h3><p>After multiple failed login attempts, your account may be temporarily locked. Wait 30 minutes and try again, or contact support.</p><h3>Browser Issues</h3><p>Clear your browser cache and cookies, or try using a different browser.</p><h3>Still Having Problems?</h3><p>Contact our support team with details about your login issue.</p>',
                'excerpt' => 'Solutions for when you can\'t log in to your account.',
                'category' => 'Troubleshooting',
                'is_published' => true,
                'featured' => true,
            ],
            [
                'title' => 'Optimizing Your Workflow',
                'content' => '<h2>Optimizing Your Workflow</h2><p>Make the most of our platform with these workflow optimization tips:</p><h3>Use Keyboard Shortcuts</h3><p>Learn and use our keyboard shortcuts to navigate and perform actions more quickly.</p><h3>Customize Your Dashboard</h3><p>Arrange your dashboard widgets to display the information most relevant to your daily tasks.</p><h3>Set Up Automation Rules</h3><p>Create automation rules to handle routine tasks without manual intervention.</p><h3>Use Filters and Tags</h3><p>Organize your tickets with tags and use filters to quickly find what you\'re looking for.</p>',
                'excerpt' => 'Tips for improving your efficiency when using our platform.',
                'category' => 'Best Practices',
                'is_published' => true,
                'featured' => false,
            ],
            [
                'title' => 'Creating Your Account',
                'content' => '<h2>Creating Your Account</h2><p>Follow these steps to create your account:</p><ol><li>Go to the registration page</li><li>Enter your email address</li><li>Create a secure password</li><li>Provide your name and other required information</li><li>Accept the terms and conditions</li><li>Click "Register"</li><li>Verify your email address by clicking the link in the confirmation email</li></ol><p>Once your email is verified, you can log in and start using the platform.</p>',
                'excerpt' => 'Step-by-step guide to creating a new account.',
                'category' => 'Registration',
                'is_published' => true,
                'featured' => false,
            ],
            [
                'title' => 'Two-Factor Authentication',
                'content' => '<h2>Setting Up Two-Factor Authentication</h2><p>Two-factor authentication (2FA) adds an extra layer of security to your account. Here\'s how to set it up:</p><ol><li>Log in to your account</li><li>Go to Profile Settings</li><li>Select the "Security" tab</li><li>Find the "Two-Factor Authentication" section</li><li>Click "Enable 2FA"</li><li>Follow the instructions to set up an authenticator app on your device</li><li>Enter the verification code from your app</li><li>Save your backup codes in a secure location</li></ol><p>With 2FA enabled, you\'ll need both your password and a verification code to log in.</p>',
                'excerpt' => 'How to enable and use two-factor authentication for enhanced security.',
                'category' => 'Security Settings',
                'is_published' => true,
                'featured' => true,
            ],
            [
                'title' => 'Error 404: Page Not Found',
                'content' => '<h2>Understanding and Resolving 404 Errors</h2><p>A 404 error means the page you\'re trying to access can\'t be found. This could happen for several reasons:</p><h3>Possible Causes</h3><ul><li>The page has been moved or deleted</li><li>You typed the URL incorrectly</li><li>The link you clicked is broken</li></ul><h3>Solutions</h3><ul><li>Check the URL for typing errors</li><li>Try navigating to the page from the main menu</li><li>Use the search function to find the content you\'re looking for</li><li>If you believe this is an error on our part, please contact support</li></ul>',
                'excerpt' => 'Explanation of 404 errors and how to resolve them.',
                'category' => 'Common Errors',
                'is_published' => true,
                'featured' => false,
            ],
        ];
        
        // Create articles
        $this->createArticles($articles, $categories, $createdCategories, $authors);
    }
    
    /**
     * Create articles from the given data.
     */
    private function createArticles($articles, $categoryData, $createdCategories, $authors)
    {
        foreach ($articles as $article) {
            $categoryName = $article['category'];
            $categoryIndex = array_search($categoryName, array_column($categoryData, 'name'));
            
            if ($categoryIndex !== false) {
                $category = $createdCategories[$categoryIndex];
                
                $authorIndex = rand(0, count($authors) - 1);
                $author = $authors[$authorIndex];
                
                $publishedAt = $article['is_published'] ? now()->subDays(rand(1, 30)) : null;
                
                KnowledgeBaseArticle::create([
                    'title' => $article['title'],
                    'slug' => Str::slug($article['title']),
                    'content' => $article['content'],
                    'excerpt' => $article['excerpt'],
                    'category_id' => $category->id,
                    'author_id' => $author->id,
                    'is_published' => $article['is_published'],
                    'published_at' => $publishedAt,
                    'featured' => $article['featured'],
                ]);
            }
        }
    }
} 