<?php

namespace Lucatume\SpMapper;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class Extending_Finder extends NodeVisitorAbstract {
	private array $parents = [];
	private array $found = [];
	private string $file;

	public function __construct( array $parents ) {
		foreach ( $parents as $class ) {
			$this->parents[ '\\' . $class ] = 1;
		}
	}

	public function enterNode( Node $node ) {
		$is_a_class_extending = $node instanceof Node\Stmt\Class_
		                        && $node->namespacedName instanceof Node\Name
		                        && $node->extends instanceof Node\Name\FullyQualified;
		if ( ! $is_a_class_extending ) {
			return;
		}

		$parent = $node->extends->toCodeString();
		if ( ! isset( $this->parents[ $parent ] ) ) {
			return;
		}

		$this->found[ $this->file ] = $node->namespacedName->toString();
	}

	public function get_found(): array {
		return $this->found;
	}

	public function set_file( string $file ) {
		$this->file = $file;
	}
}
