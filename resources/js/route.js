const Foo = { template: '<div>foo</div>' };
const Bar = { template: '<div>bar</div>' };

import current from './components/chat/current'
import archive from './components/chat/archive'
import widget from './components/create/widget'
import agents from './components/assign/agents'
import botsapp from './components/assign/botsapp'
import bots from './components/create/bots'
import account from './components/setting/account'
import premium from './components/setting/premium'
import notification from './components/setting/notification'

export default [
    { path: '/', component: current },
    { path: '/chat/current', component: current },
    { path: '/chat/archive', component: archive },
    { path: '/create/widget', component: widget },
    { path: '/create/widget/:id', component: widget },
    { path: '/create/bots', component: bots },
    { path: '/create/bots/:id', component: bots },
    { path: '/assign/agents', component: agents },
    { path: '/assign/botsapp', component: botsapp },
    { path: '/results', component: Bar },
    { path: '/settings/account', component: account },
    { path: '/settings/premium', component: premium },
    { path: '/settings/notification', component: notification },
    { path: '/faq', component: Bar },
];
