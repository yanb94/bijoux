<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210219144621 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joppedc_seo_image DROP FOREIGN KEY FK_B803CDDB7E3C61F9');
        $this->addSql('DROP TABLE joppedc_seo');
        $this->addSql('DROP TABLE joppedc_seo_image');
        $this->addSql('DROP TABLE joppedc_seo_translation');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE joppedc_seo (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE joppedc_seo_image (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, type VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, path VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, UNIQUE INDEX UNIQ_B803CDDB7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE joppedc_seo_translation (id INT AUTO_INCREMENT NOT NULL, seo_page_title VARCHAR(255) CHARACTER SET utf8 DEFAULT \'\' COLLATE `utf8_unicode_ci`, seo_og_title VARCHAR(255) CHARACTER SET utf8 DEFAULT \'\' COLLATE `utf8_unicode_ci`, seo_og_description VARCHAR(255) CHARACTER SET utf8 DEFAULT \'\' COLLATE `utf8_unicode_ci`, seo_twitter_title VARCHAR(255) CHARACTER SET utf8 DEFAULT \'\' COLLATE `utf8_unicode_ci`, seo_twitter_description VARCHAR(255) CHARACTER SET utf8 DEFAULT \'\' COLLATE `utf8_unicode_ci`, seo_twitter_site VARCHAR(255) CHARACTER SET utf8 DEFAULT \'\' COLLATE `utf8_unicode_ci`, seo_extra_tags VARCHAR(255) CHARACTER SET utf8 DEFAULT \'\' COLLATE `utf8_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE joppedc_seo_image ADD CONSTRAINT FK_B803CDDB7E3C61F9 FOREIGN KEY (owner_id) REFERENCES joppedc_seo_translation (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
