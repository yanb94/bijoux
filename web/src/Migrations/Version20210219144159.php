<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210219144159 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joppedc_seo_translation DROP FOREIGN KEY FK_9990ED9A2C2AC5D3');
        $this->addSql('DROP INDEX IDX_9990ED9A2C2AC5D3 ON joppedc_seo_translation');
        $this->addSql('DROP INDEX joppedc_seo_translation_uniq_trans ON joppedc_seo_translation');
        $this->addSql('ALTER TABLE joppedc_seo_translation DROP translatable_id, DROP locale');
        $this->addSql('ALTER TABLE sylius_product DROP FOREIGN KEY FK_677B9B7497E3DD86');
        $this->addSql('DROP INDEX UNIQ_677B9B7497E3DD86 ON sylius_product');
        $this->addSql('ALTER TABLE sylius_product DROP seo_id');
        $this->addSql('ALTER TABLE sylius_taxon DROP FOREIGN KEY FK_CFD811CA97E3DD86');
        $this->addSql('DROP INDEX UNIQ_CFD811CA97E3DD86 ON sylius_taxon');
        $this->addSql('ALTER TABLE sylius_taxon DROP seo_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joppedc_seo_translation ADD translatable_id INT NOT NULL, ADD locale VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE joppedc_seo_translation ADD CONSTRAINT FK_9990ED9A2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES joppedc_seo (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_9990ED9A2C2AC5D3 ON joppedc_seo_translation (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX joppedc_seo_translation_uniq_trans ON joppedc_seo_translation (translatable_id, locale)');
        $this->addSql('ALTER TABLE sylius_product ADD seo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product ADD CONSTRAINT FK_677B9B7497E3DD86 FOREIGN KEY (seo_id) REFERENCES joppedc_seo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_677B9B7497E3DD86 ON sylius_product (seo_id)');
        $this->addSql('ALTER TABLE sylius_taxon ADD seo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_taxon ADD CONSTRAINT FK_CFD811CA97E3DD86 FOREIGN KEY (seo_id) REFERENCES joppedc_seo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFD811CA97E3DD86 ON sylius_taxon (seo_id)');
    }
}
