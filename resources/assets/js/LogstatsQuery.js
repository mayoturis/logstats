function LogstatsQuery(url, projectToken) {
	this.url = url;
	this.projectToken = projectToken;
}

LogstatsQuery.prototype.get = function(queryData, successFunction, errorFunction) {
	$.ajax({
		type: "GET",
		url: this.url,
		data: {
			query: queryData,
			projectToken: this.projectToken
		},
		success: successFunction,
		error: errorFunction
	});
}