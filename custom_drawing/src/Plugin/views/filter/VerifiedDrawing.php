<?php

namespace Drupal\custom_drawing\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\Plugin\views\filter\FilterPluginBase;
use Drupal\views\ViewExecutable;

/**
 * Filters Drawing entites when parent needs verification.
 *
 * @ViewsFilter("verified_drawing")
 */
class VerifiedDrawing extends FilterPluginBase
{
    protected $connection;

    public function query()
    {

     //  We need to check the Node....
        $configuration = [
          'type'       => 'LEFT',
          'left_table'      => 'drawing',
          'left_field'      => 'game',
          'table' => 'node__field_feature_approved',
          'field' => 'entity_id',
          'operator'   => '=',
         ];

        $query->table_queue['drawing_game_approval'] = [
           'alias' => 'drawing_game_approval',
           'table' => 'drawing',
           'relationship' => 'node',
           'join' => $join
         ];

        $join_obj = \Drupal\views\Views::pluginManager('join')
          ->createInstance('standard', $configuration);

        $relationship = $this->query->addRelationship('approval_required', $join_obj, 'drawing');
        $this->query->addTable('node__field_feature_approved_drawing', $relationship, $join_obj, 'approval_required');

        // do the same for the field_verified
        $configuration = [
           'type'       => 'LEFT',
           'left_table'      => 'drawing',
           'left_field'      => 'id',
           'table' => 'drawing__field_verified',
           'field' => 'entity_id',
           'operator'   => '=',
          ];

        $query->table_queue['drawing_game_verified'] = [
            'alias' => 'drawing_game_verified',
            'table' => 'drawing',
            'relationship' => 'drawing__field_verified',
            'join' => $join
          ];

        $join_obj = \Drupal\views\Views::pluginManager('join')
            ->createInstance('standard', $configuration);

        $relationship = $this->query->addRelationship('drawing_game_verified', $join_obj, 'drawing');
        $this->query->addTable('drawing__field_verified', $relationship, $join_obj, 'drawing_game_verified');

        // Add Expression for where condition....
        $this->query->addWhereExpression(0, "((approval_required.field_feature_approved_value = 1 AND drawing_game_verified.field_verified_value = 1) OR (approval_required.field_feature_approved_value IS NULL OR approval_required.field_feature_approved_value = 0))");
    }
}
