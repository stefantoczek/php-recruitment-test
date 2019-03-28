<?php

use Snowdog\DevTest\Command\MigrateCommand;
use Snowdog\DevTest\Command\SitemapLoadCommand;
use Snowdog\DevTest\Command\WarmCommand;
use Snowdog\DevTest\Component\CommandRepository;
use Snowdog\DevTest\Component\Menu;
use Snowdog\DevTest\Component\Migrations;
use Snowdog\DevTest\Component\RouteRepository;
use Snowdog\DevTest\Controller\CreatePageAction;
use Snowdog\DevTest\Controller\CreateVarnishAction;
use Snowdog\DevTest\Controller\CreateVarnishLinkAction;
use Snowdog\DevTest\Controller\CreateWebsiteAction;
use Snowdog\DevTest\Controller\IndexAction;
use Snowdog\DevTest\Controller\LoginAction;
use Snowdog\DevTest\Controller\LoginFormAction;
use Snowdog\DevTest\Controller\LogoutAction;
use Snowdog\DevTest\Controller\RegisterAction;
use Snowdog\DevTest\Controller\RegisterFormAction;
use Snowdog\DevTest\Controller\SitemapImportAction;
use Snowdog\DevTest\Controller\SitemapImportPostAction;
use Snowdog\DevTest\Controller\VarnishesAction;
use Snowdog\DevTest\Controller\WebsiteAction;
use Snowdog\DevTest\Menu\LoginMenu;
use Snowdog\DevTest\Menu\RegisterMenu;
use Snowdog\DevTest\Menu\SitemapImportMenu;
use Snowdog\DevTest\Menu\VarnishesMenu;
use Snowdog\DevTest\Menu\WebsitesMenu;

RouteRepository::registerRoute('GET', '/', IndexAction::class, 'execute', RouteRepository::MIDDLEWARE_LOGGED);
RouteRepository::registerRoute('GET', '/login', LoginFormAction::class, 'execute',RouteRepository::MIDDLEWARE_GUEST);
RouteRepository::registerRoute('GET', '/sitemap-import', SitemapImportAction::class, 'execute',RouteRepository::MIDDLEWARE_LOGGED);
RouteRepository::registerRoute('POST', '/sitemap-import', SitemapImportPostAction::class, 'execute',RouteRepository::MIDDLEWARE_LOGGED);
RouteRepository::registerRoute('POST', '/login', LoginAction::class, 'execute',RouteRepository::MIDDLEWARE_GUEST);
RouteRepository::registerRoute('GET', '/logout', LogoutAction::class, 'execute',RouteRepository::MIDDLEWARE_LOGGED);
RouteRepository::registerRoute('GET', '/register', RegisterFormAction::class, 'execute',RouteRepository::MIDDLEWARE_GUEST);
RouteRepository::registerRoute('POST', '/register', RegisterAction::class, 'execute',RouteRepository::MIDDLEWARE_GUEST);
RouteRepository::registerRoute('GET', '/website/{id:\d+}', WebsiteAction::class, 'execute',RouteRepository::MIDDLEWARE_LOGGED);
RouteRepository::registerRoute('POST', '/website', CreateWebsiteAction::class, 'execute',RouteRepository::MIDDLEWARE_LOGGED);
RouteRepository::registerRoute('POST', '/page', CreatePageAction::class, 'execute',RouteRepository::MIDDLEWARE_LOGGED);
RouteRepository::registerRoute('GET', '/varnish', VarnishesAction::class, 'execute',RouteRepository::MIDDLEWARE_LOGGED);
RouteRepository::registerRoute('POST', '/varnish', CreateVarnishAction::class, 'execute',RouteRepository::MIDDLEWARE_LOGGED);
RouteRepository::registerRoute('POST', '/varnish-link', CreateVarnishLinkAction::class, 'execute',RouteRepository::MIDDLEWARE_LOGGED);

CommandRepository::registerCommand('migrate_db', MigrateCommand::class);
CommandRepository::registerCommand('warm [id]', WarmCommand::class);
CommandRepository::registerCommand('load_sitemap [username] [filename]', SitemapLoadCommand::class);

Menu::register(LoginMenu::class, 200);
Menu::register(RegisterMenu::class, 250);
Menu::register(WebsitesMenu::class, 10);
Menu::register(VarnishesMenu::class, 40);
Menu::register(SitemapImportMenu::class, 50);

Migrations::registerComponentMigration('Snowdog\\DevTest', 5);