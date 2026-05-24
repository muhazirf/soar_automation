<x-layouts.dashboard title="Settings">
    <!-- Settings Header -->
    <div class="mb-6">
        <h1 class="text-headline-lg text-on-surface">Settings</h1>
        <p class="text-body-md text-on-surface-variant">Manage your account and application preferences</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Settings (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Notification Settings -->
            <x-ui.card title="Notification Preferences">
                <form method="POST" action="{{ route('settings.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Email Alerts -->
                    <div class="flex items-center justify-between p-4 bg-surface-container rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-on-surface">Email Alerts</p>
                            <p class="text-xs text-on-surface-variant">Receive alerts via email</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notifications[email_alerts]" value="1" {{ $settings['notifications']['email_alerts'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-outline peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <!-- Push Alerts -->
                    <div class="flex items-center justify-between p-4 bg-surface-container rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-on-surface">Push Notifications</p>
                            <p class="text-xs text-on-surface-variant">Receive browser push notifications</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notifications[push_alerts]" value="1" {{ $settings['notifications']['push_alerts'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-outline peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <!-- Alert Severity -->
                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-2">Minimum Alert Severity</label>
                        <select name="notifications[alert_severity]" class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/50">
                            <option value="low" {{ $settings['notifications']['alert_severity'] === 'low' ? 'selected' : '' }}>Low - All alerts</option>
                            <option value="medium" {{ $settings['notifications']['alert_severity'] === 'medium' ? 'selected' : '' }}>Medium - Medium and above</option>
                            <option value="high" {{ $settings['notifications']['alert_severity'] === 'high' ? 'selected' : '' }}>High - High and critical only</option>
                            <option value="critical" {{ $settings['notifications']['alert_severity'] === 'critical' ? 'selected' : '' }}>Critical - Critical only</option>
                        </select>
                    </div>
                </form>
            </x-ui.card>

            <!-- Security Settings -->
            <x-ui.card title="Security">
                <form method="POST" action="{{ route('settings.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- 2FA Status -->
                    <div class="flex items-center justify-between p-4 bg-surface-container rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-on-surface">Two-Factor Authentication</p>
                            <p class="text-xs text-on-surface-variant">
                                @if($settings['security']['2fa_enabled'])
                                    <span class="text-success">Enabled</span>
                                @else
                                    <span class="text-warning">Disabled</span>
                                @endif
                            </p>
                        </div>
                        @if($settings['security']['2fa_enabled'])
                            <button type="button" class="px-4 py-2 text-sm text-error hover:bg-error/10 rounded-lg transition-colors">
                                Disable
                            </button>
                        @else
                            <button type="button" class="px-4 py-2 text-sm bg-primary text-surface rounded-lg hover:bg-primary/90 transition-colors">
                                Enable
                            </button>
                        @endif
                    </div>

                    <!-- Session Timeout -->
                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-2">Session Timeout (minutes)</label>
                        <input
                            type="number"
                            name="security[session_timeout]"
                            value="{{ $settings['security']['session_timeout'] }}"
                            min="15"
                            max="480"
                            class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/50"
                        >
                    </div>

                    <!-- Active Sessions -->
                    <div class="pt-4 border-t border-outline-variant/10">
                        <button type="button" class="w-full flex items-center justify-between p-4 bg-surface-container hover:bg-surface-container-high rounded-lg transition-colors">
                            <div>
                                <p class="text-sm font-medium text-on-surface">Manage Active Sessions</p>
                                <p class="text-xs text-on-surface-variant">View and revoke active sessions</p>
                            </div>
                            <span class="material-symbols-outlined text-primary">chevron_right</span>
                        </button>
                    </div>
                </form>
            </x-ui.card>

            <!-- API Integrations -->
            <x-ui.card title="API Integrations">
                <form method="POST" action="{{ route('settings.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- VirusTotal -->
                    <div class="flex items-center justify-between p-4 bg-surface-container rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-success">verified</span>
                            <div>
                                <p class="text-sm font-medium text-on-surface">VirusTotal</p>
                                <p class="text-xs text-on-surface-variant">File and URL analysis</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="api[virus_total_enabled]" value="1" {{ $settings['api']['virus_total_enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-outline peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <!-- Abuse.ch -->
                    <div class="flex items-center justify-between p-4 bg-surface-container rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-success">verified</span>
                            <div>
                                <p class="text-sm font-medium text-on-surface">Abuse.ch</p>
                                <p class="text-xs text-on-surface-variant">Threat intelligence feeds</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="api[abuse_ch_enabled]" value="1" {{ $settings['api']['abuse_ch_enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-outline peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <!-- ThreatFox -->
                    <div class="flex items-center justify-between p-4 bg-surface-container rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-outline">cancel</span>
                            <div>
                                <p class="text-sm font-medium text-on-surface">ThreatFox</p>
                                <p class="text-xs text-on-surface-variant">IOC sharing platform</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="api[threat_fox_enabled]" value="1" {{ $settings['api']['threatFox_enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-outline peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </form>
            </x-ui.card>
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Display Settings -->
            <x-ui.card title="Display">
                <form method="POST" action="{{ route('settings.update') }}" id="display-form" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Theme -->
                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-2">Theme</label>
                        <select name="display[theme]" class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/50">
                            <option value="dark" {{ $settings['display']['theme'] === 'dark' ? 'selected' : '' }}>Dark</option>
                            <option value="light" {{ $settings['display']['theme'] === 'light' ? 'selected' : '' }}>Light</option>
                            <option value="auto" {{ $settings['display']['theme'] === 'auto' ? 'selected' : '' }}>Auto (System)</option>
                        </select>
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-2">Timezone</label>
                        <select name="display[timezone]" class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/50">
                            <option value="UTC" {{ $settings['display']['timezone'] === 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ $settings['display']['timezone'] === 'America/New_York' ? 'selected' : '' }}>EST (UTC-5)</option>
                            <option value="America/Chicago" {{ $settings['display']['timezone'] === 'America/Chicago' ? 'selected' : '' }}>CST (UTC-6)</option>
                            <option value="Asia/Jakarta" {{ $settings['display']['timezone'] === 'Asia/Jakarta' ? 'selected' : '' }}>WIB (UTC+7)</option>
                        </select>
                    </div>

                    <!-- Time Format -->
                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-2">Time Format</label>
                        <select name="display[time_format]" class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/50">
                            <option value="24h" {{ $settings['display']['time_format'] === '24h' ? 'selected' : '' }}>24 Hour (14:30)</option>
                            <option value="12h" {{ $settings['display']['time_format'] === '12h' ? 'selected' : '' }}>12 Hour (2:30 PM)</option>
                        </select>
                    </div>
                </form>
            </x-ui.card>

            <!-- Save Button -->
            <div class="glass-card rounded-xl p-6">
                <button type="submit" form="display-form" class="w-full px-6 py-3 bg-primary text-surface rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Save All Settings
                </button>
                <p class="text-xs text-center text-on-surface-variant mt-2">
                    Changes are saved automatically
                </p>
            </div>

            <!-- Quick Actions -->
            <x-ui.card title="Quick Actions">
                <div class="space-y-2">
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-primary">download</span>
                        <span class="text-sm">Export Data</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-info">help</span>
                        <span class="text-sm">Help & Documentation</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-error/10 hover:bg-error/20 rounded-lg text-left transition-colors text-error">
                        <span class="material-symbols-outlined">warning</span>
                        <span class="text-sm">Reset All Settings</span>
                    </button>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layouts.dashboard>
