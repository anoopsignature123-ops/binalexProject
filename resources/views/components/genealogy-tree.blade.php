@props(['rootUser', 'isAdmin' => false])

<style>
    .genealogy-tree-container {
        width: 100%;
        overflow-x: auto;
        padding: 2.5rem 1rem;
        -webkit-overflow-scrolling: touch;
        position: relative;
    }
    .genealogy-tree-container::-webkit-scrollbar {
        height: 6px;
    }
    .genealogy-tree-container::-webkit-scrollbar-track {
        background: rgba(8, 10, 15, 0.5);
    }
    .genealogy-tree-container::-webkit-scrollbar-thumb {
        background: var(--border-gold);
        border-radius: 4px;
    }

    .tree-level-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 0 auto;
        min-width: max-content;
    }

    /* --- Node Wrapper for Hover Tooltip Positioning --- */
    .tree-node-wrapper {
        position: relative;
        display: inline-block;
    }

    /* --- Rich Hover Tooltip Card --- */
    .tree-hover-tooltip {
        position: absolute;
        width: 280px;
        background: rgba(10, 14, 24, 0.98);
        border: 1.5px solid var(--border-gold);
        border-radius: 14px;
        padding: 0.9rem 1.1rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.9), var(--gold-glow);
        backdrop-filter: blur(20px);
        z-index: 2000;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: all 0.25s ease;
        text-align: left;
    }
    .tree-hover-tooltip.tooltip-bottom {
        top: 105%;
        left: 50%;
        transform: translateX(-50%) translateY(-10px);
    }
    .tree-hover-tooltip.tooltip-top {
        bottom: 105%;
        left: 50%;
        transform: translateX(-50%) translateY(10px);
    }

    /* Show Tooltip on Hover for Desktop */
    @media (min-width: 993px) {
        .tree-node-wrapper:hover .tree-hover-tooltip.tooltip-bottom {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }
        .tree-node-wrapper:hover .tree-hover-tooltip.tooltip-top {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }
    }

    .tooltip-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.25rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        font-size: 0.8rem;
    }
    .tooltip-row:last-child { border-bottom: none; }
    .tooltip-label { color: var(--text-muted); font-weight: 500; }
    .tooltip-val { color: #fff; font-weight: 700; }
    .tooltip-val.gold { color: var(--gold-light); }
    .tooltip-val.cyan { color: #38bdf8; }
    .tooltip-val.green { color: #34d399; }

    .tooltip-pill {
        border-radius: 8px;
        padding: 0.4rem 0.6rem;
        margin: 0.35rem 0;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .tooltip-pill.green-pill {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #34d399;
    }
    .tooltip-pill.blue-pill {
        background: rgba(56, 189, 248, 0.15);
        border: 1px solid rgba(56, 189, 248, 0.3);
        color: #38bdf8;
    }

    /* --- Compact Sleek Mini Tree Node Card --- */
    .tree-card-node {
        background: rgba(16, 22, 34, 0.95);
        border: 1.5px solid var(--border-gold);
        border-radius: 14px;
        padding: 0.65rem 0.6rem;
        width: 150px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(15px);
        position: relative;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        z-index: 2;
    }
    .tree-card-node:hover {
        border-color: var(--gold-light);
        box-shadow: var(--gold-glow);
        transform: translateY(-3px);
    }
    .tree-card-node.root-node {
        border-color: var(--gold-primary);
        background: linear-gradient(145deg, rgba(245, 184, 46, 0.18) 0%, rgba(16, 22, 34, 0.95) 100%);
        box-shadow: 0 0 20px rgba(245, 184, 46, 0.25);
    }

    .tree-avatar {
        width: 34px;
        height: 34px;
        background: var(--gold-gradient);
        border-radius: 50%;
        margin: 0 auto 0.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
        font-weight: 800;
        font-size: 0.95rem;
        box-shadow: 0 0 10px rgba(245, 184, 46, 0.35);
    }

    .tree-user-name {
        font-size: 0.82rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .tree-code-badge {
        font-size: 0.72rem;
        color: var(--gold-light);
        font-weight: 700;
        margin-bottom: 0.35rem;
    }

    /* --- Connection Lines --- */
    .tree-line-vertical {
        width: 2px;
        height: 25px;
        background: var(--gold-gradient);
        margin: 0 auto;
        box-shadow: 0 0 8px rgba(245, 184, 46, 0.4);
    }

    .tree-branches-row {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        position: relative;
    }
    .tree-branches-row::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100% - 150px);
        height: 2px;
        background: var(--gold-gradient);
        box-shadow: 0 0 8px rgba(245, 184, 46, 0.4);
    }

    /* Icon-only Compact Action Buttons */
    .tree-action-bar {
        display: flex;
        gap: 6px;
        justify-content: center;
        align-items: center;
        margin-top: 0.45rem;
    }
    .btn-tree-act {
        width: 32px;
        height: 26px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-tree-act.gold { background: rgba(245, 184, 46, 0.2); color: var(--gold-light); border: 1px solid var(--border-gold); }
    .btn-tree-act.gold:hover { background: var(--gold-gradient); color: #000; }
    .btn-tree-act.cyan { background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
    .btn-tree-act.cyan:hover { background: #38bdf8; color: #000; }
    .btn-tree-act.info { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
    .btn-tree-act.info:hover { background: #34d399; color: #000; }

    @media (max-width: 576px) {
        .tree-card-node { width: 135px; padding: 0.55rem 0.4rem; }
        .tree-branches-row::before { width: calc(100% - 135px); }
        .tree-branches-row { gap: 1rem; }
        .btn-tree-act { width: 28px; height: 24px; font-size: 0.7rem; }
    }
</style>

<!-- Node Member Info Detail Modal -->
<div class="custom-modal-backdrop" id="nodeDetailModal">
    <div class="custom-modal-card" style="max-width: 380px; text-align: left;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-gold); padding-bottom: 0.6rem;">
            <h3 style="color: var(--gold-light); font-size: 1.1rem; margin: 0;"><i class="fa-solid fa-circle-info"></i> Member Details</h3>
            <button onclick="closeNodeModal()" style="background: transparent; border: none; color: var(--gold-light); font-size: 1.2rem; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div id="modalNodeContent">
            <!-- Dynamic Content -->
        </div>

        <div style="margin-top: 1.2rem; text-align: center;">
            <button class="btn-gold" style="padding: 0.5rem 1.4rem; font-size: 0.85rem;" onclick="closeNodeModal()">Close Window</button>
        </div>
    </div>
</div>

<div class="genealogy-tree-container">
    <div class="tree-level-wrapper">
        <!-- Root Node Wrapper with Hover Tooltip -->
        @php
            $rootTreeUrl = $isAdmin ? route('admin.users.tree', $rootUser->id) : route('user.network.tree', $rootUser->id);
            $rootSponsorTreeUrl = $rootUser->sponsor_id ? ($isAdmin ? route('admin.users.tree', $rootUser->sponsor_id) : route('user.network.tree', $rootUser->sponsor_id)) : '#';
            $rootActDirects = $rootUser->activeDirectsCount();
            $rootTotDirects = $rootUser->directs->count();
            $rootInactDirects = $rootTotDirects - $rootActDirects;
            $rootTeamStats = $rootUser->getSubtreeTeamStats($rootUser);
            $rootVol = $rootUser->getSubtreeVolume($rootUser);
            $rootPkg = $rootUser->total_staked_usd > 0 ? '$' . number_format($rootUser->total_staked_usd, 0) . ' Package' : 'No Package';

            $rootJson = json_encode([
                'name' => $rootUser->name,
                'code' => $rootUser->referral_code,
                'sponsor_code' => $rootUser->sponsor->referral_code ?? 'None (Top Root)',
                'sponsor_name' => $rootUser->sponsor->name ?? 'System Admin',
                'package' => $rootPkg,
                'directs' => "{$rootActDirects} Act, {$rootInactDirects} Inact (Tot: {$rootTotDirects})",
                'team' => "{$rootTeamStats['active']} Act, {$rootTeamStats['inactive']} Inact (Tot: {$rootTeamStats['total']})",
                'staked' => '$' . number_format($rootUser->total_staked_usd, 2),
                'volume' => '$' . number_format($rootVol, 2),
                'sponsor_url' => $rootSponsorTreeUrl,
            ]);
        @endphp

        <div class="tree-node-wrapper">
            <!-- Tooltip Card (Positioned BOTTOM for Root Node) -->
            <div class="tree-hover-tooltip tooltip-bottom">
                <div class="tooltip-row">
                    <span class="tooltip-label">Full Name</span>
                    <span class="tooltip-val">{{ $rootUser->name }}</span>
                </div>
                <div class="tooltip-row">
                    <span class="tooltip-label">Self ID</span>
                    <span class="tooltip-val gold">{{ $rootUser->referral_code }}</span>
                </div>
                <div class="tooltip-row">
                    <span class="tooltip-label">SponsorID</span>
                    @if($rootUser->sponsor)
                        <a href="{{ $rootSponsorTreeUrl }}" class="tooltip-val gold" style="text-decoration: underline;" title="Click to bring Sponsor to Top Re-root">
                            {{ $rootUser->sponsor->referral_code }} &uarr;
                        </a>
                    @else
                        <span class="tooltip-val gold">None (Top Root)</span>
                    @endif
                </div>
                <div class="tooltip-row">
                    <span class="tooltip-label">Sponsor Name</span>
                    <span class="tooltip-val cyan">{{ $rootUser->sponsor->name ?? 'System Admin' }}</span>
                </div>
                <div class="tooltip-row">
                    <span class="tooltip-label">Package Name</span>
                    <span class="tooltip-val gold">{{ $rootPkg }}</span>
                </div>

                <div class="tooltip-pill green-pill">
                    <span>Direct Members</span>
                    <span>{{ $rootActDirects }} Act, {{ $rootInactDirects }} Inact (Tot: {{ $rootTotDirects }})</span>
                </div>
                <div class="tooltip-pill blue-pill">
                    <span>Total Team Members</span>
                    <span>{{ $rootTeamStats['active'] }} Act, {{ $rootTeamStats['inactive'] }} Inact (Tot: {{ $rootTeamStats['total'] }})</span>
                </div>

                <div class="tooltip-row">
                    <span class="tooltip-label">Personal Staked</span>
                    <span class="tooltip-val green">${{ number_format($rootUser->total_staked_usd, 2) }}</span>
                </div>
                <div class="tooltip-row">
                    <span class="tooltip-label">Team Subtree Volume</span>
                    <span class="tooltip-val cyan">${{ number_format($rootVol, 2) }}</span>
                </div>
            </div>

            <!-- Sleek Compact Root Node Card -->
            <div class="tree-card-node root-node" onclick="window.location.href='{{ $rootTreeUrl }}'">
                <div class="tree-avatar">
                    <i class="fa-solid fa-crown" style="font-size: 0.75rem; margin-right: 1px;"></i>
                    {{ strtoupper(substr($rootUser->name ?? $rootUser->email, 0, 1)) }}
                </div>
                <div class="tree-user-name">{{ $rootUser->name }}</div>
                <div class="tree-code-badge">{{ $rootUser->referral_code }}</div>
                <span class="badge-status badge-{{ $rootUser->status }}" style="font-size: 0.62rem; padding: 2px 7px;">{{ strtoupper($rootUser->status) }}</span>

                <div class="tree-action-bar" onclick="event.stopPropagation();">
                    <button class="btn-tree-act info" onclick="openNodeModal({{ $rootJson }})" title="View Details Modal"><i class="fa-solid fa-circle-info"></i></button>
                </div>
            </div>
        </div>

        <!-- Level 1 Direct Connections -->
        @if($rootUser->directs->count() > 0)
            <div class="tree-line-vertical"></div>

            <div class="tree-branches-row" style="{{ $rootUser->directs->count() == 1 ? '::before { display: none; }' : '' }}">
                @foreach($rootUser->directs as $child)
                    @php
                        $childTreeUrl = $isAdmin ? route('admin.users.tree', $child->id) : route('user.network.tree', $child->id);
                        $childSponsorTreeUrl = $isAdmin ? route('admin.users.tree', $child->sponsor_id) : route('user.network.tree', $child->sponsor_id);
                        $childDirectsUrl = $isAdmin ? route('admin.users.directs', $child->id) : route('user.network.directs');
                        $childActDirects = $child->activeDirectsCount();
                        $childTotDirects = $child->directs->count();
                        $childInactDirects = $childTotDirects - $childActDirects;
                        $childTeamStats = $child->getSubtreeTeamStats($child);
                        $childVol = $child->getSubtreeVolume($child);
                        $childPkg = $child->total_staked_usd > 0 ? '$' . number_format($child->total_staked_usd, 0) . ' Package' : 'No Package';

                        $childJson = json_encode([
                            'name' => $child->name,
                            'code' => $child->referral_code,
                            'sponsor_code' => $child->sponsor->referral_code ?? $rootUser->referral_code,
                            'sponsor_name' => $child->sponsor->name ?? $rootUser->name,
                            'package' => $childPkg,
                            'directs' => "{$childActDirects} Act, {$childInactDirects} Inact (Tot: {$childTotDirects})",
                            'team' => "{$childTeamStats['active']} Act, {$childTeamStats['inactive']} Inact (Tot: {$childTeamStats['total']})",
                            'staked' => '$' . number_format($child->total_staked_usd, 2),
                            'volume' => '$' . number_format($childVol, 2),
                            'sponsor_url' => $childSponsorTreeUrl,
                        ]);
                    @endphp
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <div class="tree-line-vertical"></div>

                        <div class="tree-node-wrapper">
                            <!-- Tooltip Card for Child Node (Positioned TOP) -->
                            <div class="tree-hover-tooltip tooltip-top">
                                <div class="tooltip-row">
                                    <span class="tooltip-label">Full Name</span>
                                    <span class="tooltip-val">{{ $child->name }}</span>
                                </div>
                                <div class="tooltip-row">
                                    <span class="tooltip-label">Self ID</span>
                                    <span class="tooltip-val gold">{{ $child->referral_code }}</span>
                                </div>
                                <div class="tooltip-row">
                                    <span class="tooltip-label">SponsorID</span>
                                    <a href="{{ $childSponsorTreeUrl }}" class="tooltip-val gold" style="text-decoration: underline;" title="Click to bring Sponsor to Top Re-root">
                                        {{ $child->sponsor->referral_code ?? $rootUser->referral_code }} &uarr;
                                    </a>
                                </div>
                                <div class="tooltip-row">
                                    <span class="tooltip-label">Sponsor Name</span>
                                    <span class="tooltip-val cyan">{{ $child->sponsor->name ?? $rootUser->name }}</span>
                                </div>
                                <div class="tooltip-row">
                                    <span class="tooltip-label">Package Name</span>
                                    <span class="tooltip-val gold">{{ $childPkg }}</span>
                                </div>

                                <div class="tooltip-pill green-pill">
                                    <span>Direct Members</span>
                                    <span>{{ $childActDirects }} Act, {{ $childInactDirects }} Inact (Tot: {{ $childTotDirects }})</span>
                                </div>
                                <div class="tooltip-pill blue-pill">
                                    <span>Total Team Members</span>
                                    <span>{{ $childTeamStats['active'] }} Act, {{ $childTeamStats['inactive'] }} Inact (Tot: {{ $childTeamStats['total'] }})</span>
                                </div>

                                <div class="tooltip-row">
                                    <span class="tooltip-label">Personal Staked</span>
                                    <span class="tooltip-val green">${{ number_format($child->total_staked_usd, 2) }}</span>
                                </div>
                                <div class="tooltip-row">
                                    <span class="tooltip-label">Team Subtree Volume</span>
                                    <span class="tooltip-val cyan">${{ number_format($childVol, 2) }}</span>
                                </div>
                            </div>

                            <!-- Sleek Compact Child Node Card -->
                            <div class="tree-card-node" onclick="window.location.href='{{ $childTreeUrl }}'">
                                <div class="tree-avatar">
                                    {{ strtoupper(substr($child->name ?? $child->email, 0, 1)) }}
                                </div>
                                <div class="tree-user-name">{{ $child->name }}</div>
                                <div class="tree-code-badge">{{ $child->referral_code }}</div>
                                <span class="badge-status badge-{{ $child->status }}" style="font-size: 0.62rem; padding: 2px 7px;">{{ strtoupper($child->status) }}</span>

                                <div class="tree-action-bar" onclick="event.stopPropagation();">
                                    <a href="{{ $childTreeUrl }}" class="btn-tree-act gold" title="Re-root Node to Top"><i class="fa-solid fa-sitemap"></i></a>
                                    <a href="{{ $childDirectsUrl }}" class="btn-tree-act cyan" title="View Directs List"><i class="fa-solid fa-users"></i></a>
                                    <button class="btn-tree-act info" onclick="openNodeModal({{ $childJson }})" title="View Details Modal"><i class="fa-solid fa-circle-info"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Level 2 Sub-branch Connections -->
                        @if($child->directs->count() > 0)
                            <div class="tree-line-vertical" style="height: 20px;"></div>
                            <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
                                @foreach($child->directs as $subChild)
                                    @php
                                        $subChildTreeUrl = $isAdmin ? route('admin.users.tree', $subChild->id) : route('user.network.tree', $subChild->id);
                                        $subChildSponsorUrl = $isAdmin ? route('admin.users.tree', $subChild->sponsor_id) : route('user.network.tree', $subChild->sponsor_id);
                                        $subTeamStats = $subChild->getSubtreeTeamStats($subChild);
                                        $subVol = $subChild->getSubtreeVolume($subChild);
                                        $subPkg = $subChild->total_staked_usd > 0 ? '$' . number_format($subChild->total_staked_usd, 0) . ' Package' : 'No Package';

                                        $subJson = json_encode([
                                            'name' => $subChild->name,
                                            'code' => $subChild->referral_code,
                                            'sponsor_code' => $subChild->sponsor->referral_code ?? $child->referral_code,
                                            'sponsor_name' => $subChild->sponsor->name ?? $child->name,
                                            'package' => $subPkg,
                                            'directs' => "{$subChild->activeDirectsCount()} Act, " . ($subChild->directs->count() - $subChild->activeDirectsCount()) . " Inact (Tot: {$subChild->directs->count()})",
                                            'team' => "{$subTeamStats['active']} Act, {$subTeamStats['inactive']} Inact (Tot: {$subTeamStats['total']})",
                                            'staked' => '$' . number_format($subChild->total_staked_usd, 2),
                                            'volume' => '$' . number_format($subVol, 2),
                                            'sponsor_url' => $subChildSponsorUrl,
                                        ]);
                                    @endphp

                                    <div class="tree-node-wrapper">
                                        <!-- Tooltip Card for Subchild -->
                                        <div class="tree-hover-tooltip tooltip-top" style="width: 270px;">
                                            <div class="tooltip-row">
                                                <span class="tooltip-label">Full Name</span>
                                                <span class="tooltip-val">{{ $subChild->name }}</span>
                                            </div>
                                            <div class="tooltip-row">
                                                <span class="tooltip-label">Self ID</span>
                                                <span class="tooltip-val gold">{{ $subChild->referral_code }}</span>
                                            </div>
                                            <div class="tooltip-row">
                                                <span class="tooltip-label">SponsorID</span>
                                                <a href="{{ $subChildSponsorUrl }}" class="tooltip-val gold" style="text-decoration: underline;" title="Click to bring Sponsor to Top Re-root">
                                                    {{ $subChild->sponsor->referral_code ?? $child->referral_code }} &uarr;
                                                </a>
                                            </div>
                                            <div class="tooltip-row">
                                                <span class="tooltip-label">Package Name</span>
                                                <span class="tooltip-val gold">{{ $subPkg }}</span>
                                            </div>
                                            <div class="tooltip-pill green-pill">
                                                <span>Total Team</span>
                                                <span>{{ $subTeamStats['total'] }} Members</span>
                                            </div>
                                            <div class="tooltip-row">
                                                <span class="tooltip-label">Team Volume</span>
                                                <span class="tooltip-val cyan">${{ number_format($subVol, 2) }}</span>
                                            </div>
                                        </div>

                                        <div class="tree-card-node" style="width: 140px; padding: 0.55rem 0.5rem; background: rgba(8,10,15,0.85);" onclick="window.location.href='{{ $subChildTreeUrl }}'">
                                            <div class="tree-user-name" style="font-size: 0.78rem;">{{ $subChild->name }}</div>
                                            <div style="font-size: 0.7rem; color: var(--gold-light); margin: 2px 0;">{{ $subChild->referral_code }}</div>
                                            <span class="badge-status badge-{{ $subChild->status }}" style="font-size: 0.6rem; padding: 2px 6px;">{{ strtoupper($subChild->status) }}</span>

                                            <div class="tree-action-bar" onclick="event.stopPropagation();">
                                                <a href="{{ $subChildTreeUrl }}" class="btn-tree-act gold" title="Tree View"><i class="fa-solid fa-sitemap"></i></a>
                                                <button class="btn-tree-act info" onclick="openNodeModal({{ $subJson }})" title="Details Modal"><i class="fa-solid fa-circle-info"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div style="margin-top: 1.5rem; color: var(--text-muted); font-size: 0.85rem;">
                No downline referrals registered under this team leg yet.
            </div>
        @endif
    </div>
</div>

<script>
    function openNodeModal(data) {
        const content = document.getElementById('modalNodeContent');
        content.innerHTML = `
            <div class="tooltip-row">
                <span class="tooltip-label">Full Name</span>
                <span class="tooltip-val">${data.name}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Self ID</span>
                <span class="tooltip-val gold">${data.code}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">SponsorID</span>
                <a href="${data.sponsor_url}" class="tooltip-val gold" style="text-decoration: underline;">${data.sponsor_code} &uarr;</a>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Sponsor Name</span>
                <span class="tooltip-val cyan">${data.sponsor_name}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Package Name</span>
                <span class="tooltip-val gold">${data.package}</span>
            </div>

            <div class="tooltip-pill green-pill">
                <span>Direct Members</span>
                <span>${data.directs}</span>
            </div>
            <div class="tooltip-pill blue-pill">
                <span>Total Team Members</span>
                <span>${data.team}</span>
            </div>

            <div class="tooltip-row">
                <span class="tooltip-label">Personal Staked</span>
                <span class="tooltip-val green">${data.staked}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Team Subtree Volume</span>
                <span class="tooltip-val cyan">${data.volume}</span>
            </div>
        `;
        document.getElementById('nodeDetailModal').classList.add('show');
    }

    function closeNodeModal() {
        document.getElementById('nodeDetailModal').classList.remove('show');
    }
</script>
