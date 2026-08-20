export const getAdminWindow = () => window;

export const getAdminDocument = () => getAdminWindow().document;

const THICKBOX_ITEM_SELECTED_MESSAGE = 'pochipp:thickbox-item-selected';
const THICKBOX_MESSAGE_LISTENER_KEY = '__pochippThickboxMessageListenerRegistered';

const registerPochippThickboxMessageListener = () => {
	const adminWindow = getAdminWindow();
	if (adminWindow[THICKBOX_MESSAGE_LISTENER_KEY]) return;

	adminWindow.addEventListener('message', (event) => {
		if (event.origin !== adminWindow.location.origin) return;
		if (event.data?.type !== THICKBOX_ITEM_SELECTED_MESSAGE) return;

		const thickboxIframe = getAdminDocument().querySelector('#TB_iframeContent');
		if (!thickboxIframe || event.source !== thickboxIframe.contentWindow) return;

		const { itemData, blockId, calledAt, isMerge } = event.data.payload || {};
		if (!itemData || typeof itemData !== 'object' || Array.isArray(itemData)) return;

		if ('editor' === calledAt) {
			if (typeof adminWindow.set_block_data_at_editor !== 'function') return;
			adminWindow.set_block_data_at_editor(itemData, blockId);
		} else {
			if (typeof adminWindow.setItemMetaData !== 'function') return;
			adminWindow.setItemMetaData(itemData, Boolean(isMerge));
		}

		if (typeof adminWindow.tb_remove === 'function') {
			adminWindow.tb_remove();
		}
	});

	adminWindow[THICKBOX_MESSAGE_LISTENER_KEY] = true;
};

export const getContentDocument = (element) => {
	if (!element) return getAdminDocument();
	return element.ownerDocument || getAdminDocument();
};

export const getContentWindow = (element) => {
	const contentDocument = getContentDocument(element);
	return contentDocument.defaultView || getAdminWindow();
};

export const getPochippVars = () => getAdminWindow().pchppVars || {};

export const getPochippBlockApiVersion = (fallback = 2) => {
	const apiVersion = Number(getPochippVars().blockApiVersion || fallback);
	return apiVersion === 3 ? 3 : 2;
};

export const openPochippThickbox = (title, url) => {
	const adminWindow = getAdminWindow();
	registerPochippThickboxMessageListener();
	adminWindow.tb_show(title, url);

	const tbWindow = getAdminDocument().querySelector('#TB_window');
	if (tbWindow) {
		tbWindow.classList.add('by-pochipp');
	}
};
