import { registerFormatType, useAnchorRef } from '@wordpress/rich-text';
import { BlockControls } from '@wordpress/block-editor';
import { DropdownMenu, MenuGroup, MenuItem } from '@wordpress/components';
import { useState } from '@wordpress/element';
import Popover from './components/Popover';

const menuItems = [
	{
		title: 'インラインボタン',
		type: 'button',
	},
	{
		title: 'インラインリンク',
		type: 'link',
	},
	{
		title: 'インライン画像',
		type: 'img',
	},
];

registerFormatType('pochipp/inline-tools', {
	title: 'pochippインラインツール',
	tagName: 'pochipp-inline-tools',
	className: null,
	edit: (args) => {
		const { value, onChange, contentRef } = args;
		const [isOpen, setIsOpen] = useState(false);
		const [selectedType, setSelectedType] = useState(menuItems[0].type);
		const anchorRef = useAnchorRef({
			ref: contentRef,
			value,
		});

		const openModal = (type) => {
			setSelectedType(type);
			setIsOpen(true);
		};
		const closeModal = () => setIsOpen(false);

		return (
			<>
				<BlockControls group='other'>
					<DropdownMenu icon='pets' label='Pochippインラインツール'>
						{({ onClose }) => (
							<MenuGroup>
								{menuItems.map((menuItem) => (
									<MenuItem
										key={menuItem.type}
										icon='pets'
										iconPosition='left'
										onClick={() => {
											openModal(menuItem.type);
											onClose();
										}}
									>
										{menuItem.title}
									</MenuItem>
								))}
							</MenuGroup>
						)}
					</DropdownMenu>
				</BlockControls>
				{isOpen && (
					<Popover
						selectedType={selectedType}
						value={value}
						anchorRef={anchorRef}
						onChange={onChange}
						closePopover={closeModal}
					/>
				)}
			</>
		);
	},
});
