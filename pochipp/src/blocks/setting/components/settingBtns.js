/**
 * @WordPress dependencies
 */
import { memo } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { Icon, search, link, rotateLeft } from '@wordpress/icons';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';

/**
 * SearchBtn
 */
export const SearchBtn = memo(({ text, onClick }) => {
	return (
		<Button
			icon={<Icon icon={search} />}
			className='__searchBtn thickbox'
			isPrimary={true}
			onClick={() => {
				onClick();
			}}
		>
			{text}
		</Button>
	);
});

/**
 * UpdateBtn
 */
export const UpdateBtn = memo(({ onClick }) => {
	return (
		<Button
			icon={<Icon icon={rotateLeft} />}
			className='__updateBtn'
			isSecondary={true}
			onClick={() => {
				onClick();
			}}
		>
			最新情報に更新
		</Button>
	);
});

/**
 * SimpleLinkBtn
 */
export const SimpleLinkBtn = memo(({ text, imageId, selectCreateSimpleLink }) => {
	return (
		<MediaUploadCheck>
			<MediaUpload
				onSelect={(media) => {
					// 画像がなければ
					if (!media || !media.url) {
						return;
					}
					selectCreateSimpleLink(media.id);
				}}
				allowedTypes={'image'}
				value={imageId}
				render={({ open }) => (
					<Button
						icon={<Icon icon={link} />}
						className='__simpleLinkBtn'
						isPrimary={true}
						onClick={() => {
							open();
						}}
					>
						{text}
					</Button>
				)}
			/>
		</MediaUploadCheck>
	);
});
