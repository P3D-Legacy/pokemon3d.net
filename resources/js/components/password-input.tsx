import { useState } from 'react';
import { EyeIcon, EyeSlashIcon } from '@phosphor-icons/react';

import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';

type PasswordInputProps = {
    value?: string;
    onChange?: (value: string) => void;
    onBlur?: (e: React.FocusEvent<HTMLInputElement>) => void;
    placeholder?: string;
    disabled?: boolean;
    className?: string;
    id?: string;
    name?: string;
    minLength?: number;
    maxLength?: number;
    required?: boolean;
    autoComplete?: string;
    autoFocus?: boolean;
    'aria-invalid'?: boolean;
};

export function PasswordInput({
    value,
    onChange,
    onBlur,
    placeholder = '',
    disabled = false,
    className,
    id,
    name,
    minLength,
    maxLength,
    required = false,
    autoComplete = 'current-password',
    autoFocus = false,
    'aria-invalid': ariaInvalid,
}: PasswordInputProps) {
    const [showPassword, setShowPassword] = useState(false);

    const togglePasswordVisibility = () => {
        setShowPassword((prev) => !prev);
    };

    return (
        <div className={cn('relative', className)}>
            <Input
                type={showPassword ? 'text' : 'password'}
                {...(value !== undefined ? { value } : {})}
                onChange={onChange ? (e) => onChange(e.target.value) : undefined}
                onBlur={onBlur}
                placeholder={placeholder}
                disabled={disabled}
                id={id}
                name={name}
                minLength={minLength}
                maxLength={maxLength}
                required={required}
                autoComplete={autoComplete}
                autoFocus={autoFocus}
                aria-invalid={ariaInvalid}
                className="pr-10"
            />
            <button
                type="button"
                onClick={togglePasswordVisibility}
                disabled={disabled}
                tabIndex={0}
                aria-label={showPassword ? 'Hide password' : 'Show password'}
                className={cn(
                    'absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground transition-colors hover:text-foreground',
                    disabled && 'pointer-events-none opacity-50',
                )}
            >
                {showPassword ? <EyeSlashIcon size={18} /> : <EyeIcon size={18} />}
            </button>
        </div>
    );
}
