<?php namespace AppStorage\FlaggedContent;

use FlaggedContent;

class MongoFlaggedContentRepository implements FlaggedContentRepository {

	public function __construct( FlaggedContent $flagged ) {
		$this->flagged = $flagged;
	}

	public function instance () {
		return new FlaggedContent;
	}

	public function create ( $type, $content_id ) {
		$new_flagged_content = new FlaggedContent;
		$new_flagged_content->type = $type;
		if ( $type == 'post' ) {
			$new_flagged_content->post_id = $content_id;
		} else if ( $type == 'comment' ) {
			$new_flagged_content->comment_id = $content_id;
		}
		$validation = $new_flagged_content->validate( $new_flagged_content->toArray() );
		if ( $validation->fails() ) {
			return false;
		} else {
			$new_flagged_content->save();
			if ( $new_flagged_content->id ) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function delete ( $id ) {
		$flagged = FlaggedContent::find( $id );
		if ( $flagged instanceof FlaggedContent ) {
			$flagged->delete();
		}
	}

	public function getFlaggedOfType ( $type ) {
		return FlaggedContent::where( 'type', $type )->get();
	}

}