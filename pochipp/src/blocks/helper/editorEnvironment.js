export const getAdminWindow = () => window;

export const getAdminDocument = () => getAdminWindow().document;

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
	adminWindow.tb_show(title, url);

	const tbWindow = getAdminDocument().querySelector('#TB_window');
	if (tbWindow) {
		tbWindow.classList.add('by-pochipp');
	}
};
