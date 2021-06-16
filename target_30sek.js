// Цель будет срабатывать раз в 30 минут за один сеанс
jQuery(document).ready(function(){
	let limit = 1800000; // 30 минут
	let localStorageInitTime = localStorage.getItem('localStorageInitTime');
	if (localStorageInitTime === null) {
	    localStorage.setItem('localStorageInitTime', +new Date());
	} else if(+new Date() - localStorageInitTime > limit) {
	    localStorage.removeItem('localStorageInitTime');
	    localStorage.removeItem('localCount');
	    localStorage.setItem('localStorageInitTime', +new Date());
	}
	
	function getTarget() {
		if (localStorage.getItem('localCount') === null) {
			ym(44635873,'reachGoal','30sek');
			localStorage.setItem('localCount', '1');
		} 
	}
	
	setTimeout(getTarget, 30000);
});
