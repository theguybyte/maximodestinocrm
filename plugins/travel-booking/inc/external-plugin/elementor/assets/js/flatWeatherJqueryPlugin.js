
//flatWeatherJqueryPlugin min
//2017-05-25 v1.3
! function (t, e, s, a) { 
    function i(e, s) {
        this.element = e, this.settings = t.extend({}, p, s), this.settings.units && "auto" != this.settings.units || (this.settings.units = -1 == ["united states", "usa", "united states of america", "us"].indexOf(this.settings.country.toLowerCase()) ? "metric" : "imperial"), this.settings.forecast = Math.min(this.settings.forecast, 39), "wunderground" == this.settings.api && (this.settings.forecast = Math.min(this.settings.forecast, 3)), this._name = c, this.once = !1, this.init()
    }

    function parseWeatherResult(t, e) {
        var s = {};
        var c;
        if (t.name != null && t.name != '') {
            // for current weather
            s.city = t.name
            s.location = t.name + ", " + t.sys.country
            s.today = {}
            s.today.temp = {}
            s.today.temp.now = Math.round(t.main.temp),
                s.today.temp.min = Math.round(t.main.temp_min),
                s.today.temp.max = Math.round(t.main.temp_max),
                s.today.desc = t.weather[0].description.capitalize(),
                s.today.code = t.weather[0].id,
                s.today.wind = t.wind,
                s.today.humidity = t.main.humidity,
                s.today.pressure = t.main.pressure,
                s.today.sunrise = r(t.sys.sunrise, e.timeformat),
                s.today.sunset = r(t.sys.sunset, e.timeformat),
                s.today.day = n(new Date, e.strings.days),

                s.forecast = []
        } else {
            s.city = t.city.name
            s.location = t.city.name + ", " + t.city.country
            s.today = {}
            s.today.temp = {}
            let today = t.list[0]
            s.today.temp.now = Math.round(today.main.temp),
                s.today.temp.min = Math.round(today.main.temp_min),
                s.today.temp.max = Math.round(today.main.temp_max),
                s.today.desc = today.weather[0].description.capitalize(),
                s.today.code = today.weather[0].id,
                s.today.wind = today.wind,
                s.today.humidity = today.main.humidity,
                s.today.pressure = today.main.pressure,
                s.today.sunrise = r(null, e.timeformat),
                s.today.sunset = r(null, e.timeformat),
                s.today.day = n(new Date, e.strings.days),

                s.forecast = []
            let forecast_arr = []
            for (c = 0; c < t.list.length; c++) {
                if (t.list[c].dt != undefined && Array.isArray(t.list)) {
                    // console.log(t.list[c].dt)
                    let dt = new Date(1e3 * (t.list[c].dt));
                    // console.log(dt)
                    // console.log(dt.getDay())
                    if (forecast_arr[dt.getDay()] == null) {
                        forecast_arr[dt.getDay()] = {}
                        forecast_arr[dt.getDay()].day = n(dt, e.strings.days)
                        forecast_arr[dt.getDay()].main = [t.list[c].weather[0].main]
                        forecast_arr[dt.getDay()].code = [t.list[c].weather[0].id]
                        forecast_arr[dt.getDay()].icon = [t.list[c].weather[0].icon]
                        forecast_arr[dt.getDay()].desc = [t.list[c].weather[0].desc]
                        forecast_arr[dt.getDay()].min = [Math.round(t.list[c].main.temp_min)]
                        forecast_arr[dt.getDay()].max = [Math.round(t.list[c].main.temp_max)]
                    } else {
                        forecast_arr[dt.getDay()].main.push(t.list[c].weather[0].main)
                        forecast_arr[dt.getDay()].code.push(t.list[c].weather[0].id)
                        forecast_arr[dt.getDay()].icon.push(t.list[c].weather[0].icon)
                        forecast_arr[dt.getDay()].desc.push(t.list[c].weather[0].desc)
                        forecast_arr[dt.getDay()].min.push(Math.round(t.list[c].main.temp_min))
                        forecast_arr[dt.getDay()].max.push(Math.round(t.list[c].main.temp_max))
                    }
                }
            }

            // console.log(forecast_arr)

            if (forecast_arr.length) {
                forecast_arr.forEach((dval, index) => {
                    // console.log(index)
                    (p = {}).day = dval.day,
                        p.main = dval.main[0],
                        p.code = dval.code[0],
                        p.icon = dval.icon[0],
                        p.desc = dval.desc[0],
                        p.temp = {
                            max: Math.max.apply(null, dval.max),
                            min: Math.min.apply(null, dval.min)
                        }
                    s.forecast.push(p)
                })
            }
        }
        return s
    }

    function n(t, e) {
        return e[t.getDay()]
    }
    function remove_duplicates_es6(arr) { 
        let unique_array = []
        arr.map((item, key) => unique_array[item.day] = item)
        return unique_array
    }
    function r(t, e) {
        if (t == null) return false
        var s = (t = new Date(1e3 * t)).getHours(),
            a = t.getMinutes(),
            i = s >= 12 ? "PM" : "AM";
        return "24" == e && (i = "", s = s < 10 ? "0" + s : s), "12" == e && (s %= 12), (s = s || 12) + ":" + (a = a < 10 ? "0" + a : a) + " " + i
    }

    function d(t, e, s, a) {
        var i = e;
        return i >= 0 && i <= 11.25 || i > 348.75 && i <= 360 ? i = a[0] : i > 11.25 && i <= 33.75 ? i = a[1] : i > 33.75 && i <= 56.25 ? i = a[2] : i > 56.25 && i <= 78.75 ? i = a[3] : i > 78.75 && i <= 101.25 ? i = a[4] : i > 101.25 && i <= 123.75 ? i = a[5] : i > 123.75 && i <= 146.25 ? i = a[6] : i > 146.25 && i <= 168.75 ? i = a[7] : i > 168.75 && i <= 191.25 ? i = a[8] : i > 191.25 && i <= 213.75 ? i = a[9] : i > 213.75 && i <= 236.25 ? i = a[10] : i > 236.25 && i <= 258.75 ? i = a[11] : i > 258.75 && i <= 281.25 ? i = a[12] : i > 281.25 && i <= 303.75 ? i = a[13] : i > 303.75 && i <= 326.25 ? i = a[14] : i > 326.25 && i <= 348.75 && (i = a[15]), i || (i = ""), i + " " + t + " " + ("metric" == s ? "km/h" : "mph")
    }
    var c = "flatWeatherPlugin",
        p = {
            location: "Boston, MA",
            country: "USA",
            zmw: "02108.1.99999",
            displayCityNameOnly: !1,
            api: "openweathermap",
            forecast: 50,
            apikey: "",
            view: "full",
            render: !0,
            loadingAnimation: !0,
            strings: {
                days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                min: "Min",
                max: "Max",
                direction: ["N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW"]
            },
            timeformat: "12",
            lang: "EN",
            units: "metric",
        },
        l = {
            // openweathermap: ["http://api.openweathermap.org/data/2.5/weather", "http://api.openweathermap.org/data/2.5/forecast/daily"],
            openweathermap: ["https://api.openweathermap.org/data/2.5/weather", "https://api.openweathermap.org/data/2.5/forecast"],
            yahoo: ["https://query.yahooapis.com/v1/public/yql"],
            wunderground: ["https://api.wunderground.com/api/apikey/conditions/forecast/astronomy/"]
        };
    t.extend(i.prototype, {
        init: function () {
            this.settings.render && (this.settings.loadingAnimation && !this.once && (this.loading = t("<div/>", {
                id: "flatWeatherLoading",
                class: "wi loading"
            }), this.loading.appendTo(this.element)), this.fetchWeather().then(this.render, this.error)), this.once = !0
        },
        fetchWeather: function () {
            // console.log(this)
            var e = this,
                s = new t.Deferred,
                a = [],
                i = this.settings.location + (this.settings.country != null && this.settings.country != '' ? ',' + this.settings.country : '')


            let ajaxData = {
                action: 'fetch_weather',
                location: i,
                view: this.settings.view
            }
            if (this.settings.lat != null && this.settings.lon != null && this.settings.lat && this.settings.lon) {
                ajaxData['lat'] = this.settings.lat
                ajaxData['lon'] = this.settings.lon
            }
            // console.log(ajaxData)
            let d = t.ajax({
                type: "POST",
                url: ajax.ajaxUrl,
                data: ajaxData,
                dataType: "json",
            })


            return t.when.call(this, d).done(function (res) {
                //  console.log(res)
                if (res.success == true) {
                    let result = res.result
                    if (result.cod == '200') {
                        let i = parseWeatherResult(result, e.settings);
                        // console.log(i)
                        e._weather = i
                        t.data(e.element, "weather", i)
                        s.resolve(i, e)
                    } else {
                        s.reject(result, e)
                    }
                } else {
                    // ajax request error -> not return data
                    s.reject(res, e)
                }

            }).fail(function (t) {
                s.reject(t, e)
            }), s
        },
        error: function (e, s) {
            // console.log(e)

            s || (s = this), s.settings.loadingAnimation && s.settings.render && s.loading.remove(), "openweathermap" == s.settings.api ? e = null != e.error ? e.error : 'Weather request error. Please make sure that your api is entered.' : "yahoo" == s.settings.api ? e = e.query.results ? "Error: " + e.query.results.channel.item.title + ". See console log for details." : "Error: no results. See console log for details." : "wunderground" == s.settings.api && (e = e.response.error.type + " See console log for details.");
            var a = t("<div/>", {
                class: "flatWeatherPlugin " + s.settings.view
            });
            return t("<h2/>").text("Error").appendTo(a), t("<p/>").text(e).appendTo(a), t(s.element).html(a), t(s.element)
        },
        render: function (e, s) {
            s || (s = this, e = this._weather);
            var a = "metric" == s.settings.units ? "&#176;C" : "&#176;F";
            s.settings.loadingAnimation && s.settings.render && s.loading.remove();
            var i = t("<div/>", {
                class: "flatWeatherPlugin " + s.settings.view
            });
            if (s.settings.displayCityNameOnly ? t("<h2/>").text(e.city).appendTo(i ) : t("<h2/>").text(e.location).appendTo(i), "forecast" != s.settings.view) {
                var o = t("<div/>", {
                    class: "wiToday"
                }),
                    n = t("<div/>", {
                        class: "wiIconGroup"
                    });
                t("<div/>", {
                    class: "wi wi" + e.today.code
                }).appendTo(n),
                    // t("<p/>", {
                    // class: "wiText"
                // }).text(e.today.desc).appendTo(n), 
                    
                    n.appendTo(o), 
                t("<p/>",
                    {
                    class: "wiTemperature"
                    }).html(e.today.temp.now + "<sup>" + a + "</sup>").prependTo(o), o.appendTo(i),
                    t("<p/>", {
                        class: "wiTemmax"
                    }).html(s.settings.strings.max + ": " + e.today.temp.max + "<sup>" + a + "</sup>").appendTo(o),
                    t("<p/>", {
                        class: "wiTemmin"
                    }).html(s.settings.strings.min + ": " + e.today.temp.min + "<sup>" + a + "</sup>").appendTo(o);
            }
            return t(s.element).html(i), t(s.element)
        }
    }), t.fn[c] = function (e, s) {
        return t.isFunction(i.prototype[e]) ? this.data("plugin_" + c)[e](s) : this.each(function () {
            if (!t.data(this, "plugin_" + c)) {
                var s = new i(this, e);
                return t.data(this, "plugin_" + c, s)
            }
        })
    }, String.prototype.capitalize = function () {
        return this.charAt(0).toUpperCase() + this.slice(1)
    }
}(jQuery, window, document); 