<?php
namespace ANSR\Core\Service\Encryption;

/**
 * @author Ivan Yonkov <ivanynkv@gmail.com>
 */
class BCryptEncryptionService implements EncryptionServiceInterface
{
    public function encrypt(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}


