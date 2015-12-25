<?php
require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskProductExpression extends \Workflow\Task
{
    public function init() {

        $this->addPreset("Condition", "condition", array(
            'toModule' => 'Products',
            'fromModule' => $this->getModuleName()
        ));

    }

    public function handleTask(&$context) {
		/* Insert here source code to execute the task */
		$products = $context->exportInventory();
        $products = $products['listitems'];

        $checked = new \Workflow\ConditionCheck();
        $logger = new \Workflow\ConditionLogger();
        $conditions = $this->get('condition');
        $expression = $this->get('expression');

        $environment = $context->getEnvironment();

        foreach($products as $product) {
            $productContext = \Workflow\VTEntity::getForId($product['productid'], 'Products');
            $productContext->loadEnvironment($environment);

            $checked->setLogger($logger);
            $return = $checked->check($conditions, $productContext);

            $logs = $logger->getLogs();
            $logs[] = "Complete Result: ".intval($return);
            $this->setStat($logs);

            $logger->clearLogs();

            if($return == true) {
                $parser = new \Workflow\ExpressionParser($expression, $productContext, false); # Last Parameter = DEBUG
                $parser->setVariable('quantity', $product['quantity']);
                $parser->setVariable('unitprice', $product['unitprice']);
                $parser->setVariable('discount_amount', $product['discount_amount']);
                $parser->setVariable('discount_percent', $product['discount_percent']);

                try {
                    $parser->run();
                } catch(\Workflow\ExpressionException $exp) {
                    Workflow2::error_handler(E_EXPRESSION_ERROR, $exp->getMessage(), "", "");
                }

                $environment = $productContext->getEnvironment();

                if($parser->getReturn() === 'stop') {
                    break;
                }
            }

        }

        $context->loadEnvironment($environment);

		return "yes";
    }
	
    public function beforeGetTaskform($viewer) {
		/* Insert here source code to create custom configurations pages */
    }	
    public function beforeSave(&$values) {
		/* Insert here source code to modify the values the user submit on configuration */
    }	
}
