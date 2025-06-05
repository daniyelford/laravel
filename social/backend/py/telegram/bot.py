from telethon import TelegramClient
import sys
import json

# باید این‌ها رو از https://my.telegram.org بگیری
api_id = YOUR_API_ID  # عددی که از تلگرام می‌گیری
api_hash = 'YOUR_API_HASH'  # رشته hash

def get_channel_info(channel_username):
    client = TelegramClient('session_name', api_id, api_hash)
    
    with client:
        channel = client.get_entity(channel_username)
        participants = client.get_participants(channel)
        return {
            'title': channel.title,
            'username': channel.username,
            'id': channel.id,
            'participants_count': len(participants),
            'about': getattr(channel, 'about', ''),
        }

if __name__ == "__main__":
    input_data = json.load(sys.stdin)
    channel_username = input_data.get('channel_username')
    
    try:
        info = get_channel_info(channel_username)
        print(json.dumps(info))
    except Exception as e:
        print(json.dumps({'error': str(e)}))

# from telegram import Update
# from telegram.ext import ApplicationBuilder, CommandHandler, ContextTypes
# import requests
# import logging

# BOT_TOKEN = 'اینجا توکن باتت رو بذار'
# LARAVEL_API_URL = 'https://your-domain.ir/api/group-members'
# SECRET_KEY = 'your-secret-key'  # برای امنیت بیشتر سمت Laravel

# logging.basicConfig(
#     format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
#     level=logging.INFO
# )

# async def start(update: Update, context: ContextTypes.DEFAULT_TYPE):
#     chat = update.effective_chat

#     if chat.type not in ['group', 'supergroup']:
#         await update.message.reply_text("این دستور فقط داخل گروه کار می‌کنه.")
#         return

#     admins = await context.bot.get_chat_administrators(chat.id)

#     data = []
#     for admin in admins:
#         user = admin.user
#         data.append({
#             'id': user.id,
#             'username': user.username,
#             'first_name': user.first_name,
#             'last_name': user.last_name,
#         })

#     try:
#         response = requests.post(LARAVEL_API_URL, json={
#             'secret': SECRET_KEY,
#             'group_id': chat.id,
#             'members': data
#         })

#         if response.status_code == 200:
#             await update.message.reply_text(f"{len(data)} ادمین ارسال شد به سرور.")
#         else:
#             await update.message.reply_text("❌ خطا در ارسال به سرور.")
#     except Exception as e:
#         logging.error(f"خطا: {e}")
#         await update.message.reply_text("❌ خطا در اتصال به سرور.")

# app = ApplicationBuilder().token(BOT_TOKEN).build()

# app.add_handler(CommandHandler("start", start))

# app.run_polling()
