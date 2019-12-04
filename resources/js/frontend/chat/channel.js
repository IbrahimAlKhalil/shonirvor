import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

const echo = new Echo({
    broadcaster: 'pusher',
    key: '5a3bf510cd645f292be8',
    cluster: 'ap2',
    encrypted: true
})

const badge = document.getElementById('chat-count-badge')

fetch(window.routes.getConversations).then(response => {
    response.json().then(conversations => {
        conversations.forEach(conversation => {
            echo.join(`c-${conversation.id}`)
                .listen('.m', e => {
                    const num = parseInt(badge.innerText) || 0

                    badge.innerText = num + 1
                })
                .listen('.rc', () => {
                    console.log('Conversation removed')
                })
        })
    })
})
