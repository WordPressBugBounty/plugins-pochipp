import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import { getPochippBlockApiVersion } from '@blocks/helper/editorEnvironment';

const { apiVersion, name, title, supports } = metadata;

registerBlockType(name, {
	apiVersion: getPochippBlockApiVersion(apiVersion),
	title,
	supports,
	attributes: metadata.attributes,
	edit: () => {
		return null;
	},
	save: () => {
		return null;
	},
});
