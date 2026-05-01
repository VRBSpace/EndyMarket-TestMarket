<!DOCTYPE html>
<html lang="bg">
    <head>
        <meta charset="utf-8">
        <title>Възстановяване на парола</title>
    </head>
    <body style="margin:0;padding:24px;background:#f4f5f7;font-family:Arial,Helvetica,sans-serif;color:#111213;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #eeeeee;border-radius:12px;">
            <tr>
                <td style="padding:32px 24px 16px;text-align:center;">
                    <img src="https://media.testmarketbg.com/portal_images/logo/ND_market_logotype_2025.webp" alt="ND Market" style="max-width:220px;width:100%;height:auto;margin:0 auto 16px;">
                    <h1 style="margin:0 0 12px;font-size:24px;line-height:1.3;">Възстановяване на парола</h1>
                    <p style="margin:0 0 16px;font-size:14px;line-height:1.6;">Получихме заявка за смяна на паролата за вашия профил<?= !empty($user['username']) ? ', ' . esc($user['username']) : '' ?>.</p>
                    <p style="margin:0 0 24px;font-size:14px;line-height:1.6;">Линкът е валиден <?= (int) $ttlMinutes ?> минути.</p>
                    <a href="<?= esc($resetLink) ?>" style="display:inline-block;padding:12px 24px;background:#ef3d32;color:#ffffff;text-decoration:none;border-radius:4px;font-weight:bold;">Смени паролата</a>
                </td>
            </tr>
            <tr>
                <td style="padding:0 24px 32px;font-size:13px;line-height:1.6;color:#6b6f76;">
                    Ако не сте заявили тази смяна, игнорирайте този имейл.
                    <br><br>
                    Ако бутонът не работи, използвайте този линк:
                    <br>
                    <a href="<?= esc($resetLink) ?>" style="color:#ef3d32;word-break:break-all;"><?= esc($resetLink) ?></a>
                </td>
            </tr>
        </table>
    </body>
</html>
