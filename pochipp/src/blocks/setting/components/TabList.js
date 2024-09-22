import { memo } from '@wordpress/element';

export default memo(({ tabs, selectedTab, setSelectedTab }) => {
	return (
		<nav className='__tabs'>
			<div className='__tablist'>
				{Object.values(tabs).map(({ key, label }) => (
					<button key={key} className={key === selectedTab && '-selected'} onClick={() => setSelectedTab(key)}>
						{label}
					</button>
				))}
			</div>
		</nav>
	);
});
