<?php

namespace Lucatume\SpMapper;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class Method_Finder extends NodeVisitorAbstract {
	private string $file;
	protected array $public_methods = [];
	private array $private_methods = [];

	public function __construct( string $file ) {
		$this->file = $file;
	}

	public function enterNode( Node $node ) {
		if ( ! $node instanceof Node\Stmt\ClassMethod ) {
			return;
		}

		$is_public   = $node->isPublic();
		$start_line  = $node->getAttribute( 'startLine' );
		$end_line    = $node->getAttribute( 'endLine' );
		$method_name = $node->name->name;
		$data        = [ 'start_line' => $start_line, 'end_line' => $end_line ];
		if ( $is_public ) {
			$this->public_methods[ $method_name ] = $data;
		} else {
			$this->private_methods[ $method_name ] = $data;
		}
	}

	public function get_public_methods(): array {
		return $this->public_methods;
	}

	public function get_private_methods(): array {
		return $this->private_methods;
	}

	public function get_avg_method_loc(): int {
		$all_methods = array_merge( $this->public_methods, $this->private_methods );

		return (int) ( $this->get_max_method_loc() / count( $all_methods ) );
	}

	public function get_max_method_loc(): int {
		$all_methods = array_merge( $this->public_methods, $this->private_methods );

		return array_reduce( $all_methods, static function ( $carry, $item ) {
			return max( $carry, $item['end_line'] - $item['start_line'] );
		}, 0 );
	}

}
