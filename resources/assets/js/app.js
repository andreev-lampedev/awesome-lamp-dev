require('./bootstrap');

window.hljs = require('highlight.js');

hljs.initHighlightingOnLoad();

if (!! document.getElementById('vue-app')) {

    window.Vue = require('vue');

    Vue.directive('click-outside', {
        bind: function(el, binding, vNode) {
            // Provided expression must evaluate to a function.
            if (typeof binding.value !== 'function') {
                const compName = vNode.context.name;
                let warn = `[Vue-click-outside:] provided expression '${
                    binding.expression
                }' is not a function, but has to be`;
                if (compName) {
                    warn += `Found in component '${compName}'`;
                }

                console.warn(warn);
            }
            // Define Handler and cache it on the element
            const bubble = binding.modifiers.bubble;
            const handler = e => {
                if (bubble || (!el.contains(e.target) && el !== e.target)) {
                    binding.value(e);
                }
            };
            el.__vueClickOutside__ = handler;

            // add Event Listeners
            document.addEventListener('click', handler);
        },

        unbind: function(el, binding) {
            // Remove Event Listeners
            document.removeEventListener('click', el.__vueClickOutside__);
            el.__vueClickOutside__ = null;
        }
    });

    Vue.component('admin-dropdown', require('./components/AdminDropDown.vue'));
    Vue.component('collaborator-select', require('./components/CollaboratorSelect.vue'));
    Vue.component('package-card', require('./components/PackageCard.vue'));
    Vue.component('package-detail', require('./components/PackageDetail.vue'));
    Vue.component('package-detail-frame', require('./components/PackageDetailFrame.vue'));
    Vue.component('package-review-create', require('./components/PackageReviewCreate.vue'));
    Vue.component('package-screenshot', require('./components/PackageScreenshot.vue'));
    Vue.component('package-screenshots', require('./components/PackageScreenshots.vue'));
    Vue.component('package-screenshots-dropzone', require('./components/PackageScreenshotsDropzone.vue'));
    Vue.component('package-screenshots-list', require('./components/PackageScreenshotsList.vue'));
    Vue.component('rating-count-bar', require('./components/RatingCountBar.vue'));
    Vue.component('tag-select', require('./components/TagSelect.vue'));
    Vue.component('title-icon', require('./components/TitleIcon.vue'));

    Vue.component('passport-clients', require('./components/passport/Clients.vue'));
    Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
    Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

    Vue.mixin({
        methods: {
            route: route,
        },
        computed: {
            awesomelampdev: function() {
                return window.awesomelampdev;
            },
        }
    });

    let app = new Vue({
        el: '#vue-app',
        delimiters: ['${', '}'],
    });
}
